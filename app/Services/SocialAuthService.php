<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Vérifie les jetons d'identité (idToken) émis par Google et Apple.
 *
 * Le but est d'empêcher qu'un client fournisse simplement un e-mail arbitraire :
 * on n'accepte l'e-mail QUE s'il provient d'un jeton réellement signé par le
 * fournisseur et destiné à notre application.
 */
class SocialAuthService
{
    /**
     * Vérifie un jeton social et retourne l'e-mail vérifié, ou null si invalide.
     *
     * @return string|null  E-mail vérifié (en minuscules) ou null
     */
    public function verifiedEmail(string $provider, string $token): ?string
    {
        try {
            return match ($provider) {
                'google' => $this->verifyGoogle($token),
                'apple'  => $this->verifyApple($token),
                default  => null,
            };
        } catch (\Throwable $e) {
            Log::warning('Social token verification failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Vérifie un idToken Google via l'endpoint tokeninfo.
     * Google ne renvoie une réponse valide que si le jeton est authentique et non expiré.
     */
    private function verifyGoogle(string $token): ?string
    {
        $response = Http::timeout(10)->get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $token,
        ]);

        if (!$response->ok()) {
            return null;
        }

        $data = $response->json();

        // L'émetteur doit être Google.
        $iss = $data['iss'] ?? '';
        if (!in_array($iss, ['accounts.google.com', 'https://accounts.google.com'], true)) {
            return null;
        }

        // L'audience doit correspondre à l'un de NOS client IDs (sinon un jeton
        // émis pour une autre app pourrait être rejoué ici).
        $allowedAudiences = array_filter(array_map('trim', explode(',', (string) config('services.google.client_ids'))));
        if (!empty($allowedAudiences) && !in_array($data['aud'] ?? '', $allowedAudiences, true)) {
            Log::warning('Google token audience mismatch', ['aud' => $data['aud'] ?? null]);
            return null;
        }

        // L'e-mail doit être présent et vérifié par Google.
        $emailVerified = filter_var($data['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN);
        if (empty($data['email']) || !$emailVerified) {
            return null;
        }

        return strtolower($data['email']);
    }

    /**
     * Vérifie un identityToken Apple (JWT RS256) contre les clés publiques d'Apple.
     */
    private function verifyApple(string $token): ?string
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;

        $header = json_decode($this->base64UrlDecode($encodedHeader), true);
        $payload = json_decode($this->base64UrlDecode($encodedPayload), true);
        $signature = $this->base64UrlDecode($encodedSignature);

        if (!is_array($header) || !is_array($payload) || !isset($header['kid'], $header['alg'])) {
            return null;
        }
        if ($header['alg'] !== 'RS256') {
            return null;
        }

        // Récupérer la clé publique Apple correspondant au 'kid' du jeton.
        $keys = Http::timeout(10)->get('https://appleid.apple.com/auth/keys')->json('keys');
        if (!is_array($keys)) {
            return null;
        }

        $jwk = collect($keys)->firstWhere('kid', $header['kid']);
        if (!$jwk) {
            return null;
        }

        $publicKey = $this->jwkToPem($jwk);
        if (!$publicKey) {
            return null;
        }

        // Vérifier la signature RS256.
        $verified = openssl_verify(
            "{$encodedHeader}.{$encodedPayload}",
            $signature,
            $publicKey,
            OPENSSL_ALGO_SHA256
        );

        if ($verified !== 1) {
            return null;
        }

        // L'émetteur doit être Apple et le jeton non expiré.
        if (($payload['iss'] ?? '') !== 'https://appleid.apple.com') {
            return null;
        }
        if (($payload['exp'] ?? 0) < time()) {
            return null;
        }

        // L'audience doit correspondre à l'un de nos bundle IDs (si configuré).
        $allowedAudiences = array_filter(array_map('trim', explode(',', (string) config('services.apple.client_ids'))));
        if (!empty($allowedAudiences) && !in_array($payload['aud'] ?? '', $allowedAudiences, true)) {
            Log::warning('Apple token audience mismatch', ['aud' => $payload['aud'] ?? null]);
            return null;
        }

        if (empty($payload['email'])) {
            return null;
        }

        return strtolower($payload['email']);
    }

    /**
     * Convertit une clé JWK (modulus/exponent) en clé publique PEM utilisable par openssl.
     */
    private function jwkToPem(array $jwk): ?string
    {
        if (!isset($jwk['n'], $jwk['e'])) {
            return null;
        }

        $modulus = $this->base64UrlDecode($jwk['n']);
        $exponent = $this->base64UrlDecode($jwk['e']);

        // Encodage DER minimal d'une clé publique RSA (SubjectPublicKeyInfo).
        $modulus = "\x00" . $modulus;

        $modulusEncoded = $this->derInteger($modulus);
        $exponentEncoded = $this->derInteger($exponent);

        $rsaPublicKey = $this->derSequence($modulusEncoded . $exponentEncoded);
        $bitString = "\x03" . $this->derLength(strlen($rsaPublicKey) + 1) . "\x00" . $rsaPublicKey;

        // OID rsaEncryption : 1.2.840.113549.1.1.1
        $algorithmIdentifier = $this->derSequence(
            "\x06\x09\x2a\x86\x48\x86\xf7\x0d\x01\x01\x01" . "\x05\x00"
        );

        $publicKeyInfo = $this->derSequence($algorithmIdentifier . $bitString);

        $pem = "-----BEGIN PUBLIC KEY-----\n"
            . chunk_split(base64_encode($publicKeyInfo), 64, "\n")
            . "-----END PUBLIC KEY-----\n";

        return $pem;
    }

    private function derInteger(string $bytes): string
    {
        return "\x02" . $this->derLength(strlen($bytes)) . $bytes;
    }

    private function derSequence(string $bytes): string
    {
        return "\x30" . $this->derLength(strlen($bytes)) . $bytes;
    }

    private function derLength(int $length): string
    {
        if ($length < 128) {
            return chr($length);
        }
        $bytes = '';
        while ($length > 0) {
            $bytes = chr($length & 0xff) . $bytes;
            $length >>= 8;
        }
        return chr(0x80 | strlen($bytes)) . $bytes;
    }

    private function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
