<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $projectId;
    protected $credentialsPath;
    protected $fcmUrl;

    public function __construct()
    {
        $this->credentialsPath = config('services.firebase.credentials');
        $this->projectId = config('services.firebase.project_id');
        $this->fcmUrl = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
    }

    /**
     * Obtenir un access token OAuth2 pour Firebase
     */
    protected function getAccessToken()
    {
        // Cache le token pendant 50 minutes (il expire après 60 min)
        return Cache::remember('firebase_access_token', 50 * 60, function () {
            if (!$this->credentialsPath || !file_exists($this->credentialsPath)) {
                Log::error('Firebase credentials file not found: ' . $this->credentialsPath);
                return null;
            }

            $credentials = json_decode(file_get_contents($this->credentialsPath), true);

            if (!$credentials) {
                Log::error('Invalid Firebase credentials file');
                return null;
            }

            // Créer le JWT
            $header = [
                'alg' => 'RS256',
                'typ' => 'JWT',
            ];

            $now = time();
            $payload = [
                'iss' => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
            ];

            $base64Header = $this->base64UrlEncode(json_encode($header));
            $base64Payload = $this->base64UrlEncode(json_encode($payload));

            $signatureInput = $base64Header . '.' . $base64Payload;

            // Signer avec la clé privée
            $privateKey = openssl_pkey_get_private($credentials['private_key']);
            openssl_sign($signatureInput, $signature, $privateKey, 'SHA256');
            $base64Signature = $this->base64UrlEncode($signature);

            $jwt = $signatureInput . '.' . $base64Signature;

            // Échanger le JWT contre un access token
            try {
                $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]);

                if ($response->successful()) {
                    return $response->json()['access_token'];
                }

                Log::error('Firebase token exchange failed: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('Firebase token exchange error: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Encoder en base64 URL-safe
     */
    protected function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Envoyer une notification push à un utilisateur
     */
    public function sendNotification($fcmToken, $title, $body, $data = [])
    {
        if (!$fcmToken) {
            return false;
        }

        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            Log::warning('No Firebase access token available');
            return false;
        }

        try {
            $message = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => array_map('strval', $data), // FCM v1 requiert des strings
                    'android' => [
                        'priority' => 'high',
                        'notification' => [
                            'sound' => 'default',
                            'channel_id' => 'supriz_notifications',
                        ],
                    ],
                    'apns' => [
                        'payload' => [
                            'aps' => [
                                'sound' => 'default',
                                'badge' => 1,
                            ],
                        ],
                    ],
                ],
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->fcmUrl, $message);

            if ($response->successful()) {
                Log::info('Firebase notification sent successfully');
                return true;
            }

            Log::error('Firebase notification failed: ' . $response->body());

            // Si le token est invalide, on clear le cache
            if ($response->status() === 401) {
                Cache::forget('firebase_access_token');
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Firebase notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer une notification de vue de message
     */
    public function sendViewNotification($user, $message)
    {
        if (!$user->fcm_token) {
            return false;
        }

        return $this->sendNotification(
            $user->fcm_token,
            'Nouvelle vue sur votre message',
            "Quelqu'un a vu votre message \"{$message->titre}\"",
            [
                'type' => 'message_view',
                'message_id' => $message->id,
                'message_slug' => $message->slug,
            ]
        );
    }

    /**
     * Envoyer une notification de réaction
     */
    public function sendReactionNotification($user, $message, $emoji)
    {
        if (!$user->fcm_token) {
            return false;
        }

        return $this->sendNotification(
            $user->fcm_token,
            'Nouvelle réaction ' . $emoji,
            "Une réaction {$emoji} vient d'arriver… ton message a fait son effet !",
            [
                'type' => 'message_reaction',
                'message_id' => $message->id,
                'message_slug' => $message->slug,
                'emoji' => $emoji,
            ]
        );
    }
}
