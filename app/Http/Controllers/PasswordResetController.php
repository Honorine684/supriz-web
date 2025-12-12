<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Envoie un email de réinitialisation de mot de passe
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // Générer un token unique
        $token = Str::random(64);

        // Supprimer les anciens tokens pour cet email
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        // Insérer le nouveau token
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => hash('sha256', $token),
            'created_at' => now(),
        ]);

        // Générer l'URL de réinitialisation
        $resetUrl = route('password.reset.form', ['token' => $token, 'email' => $user->email]);

        // Envoyer l'email
        Mail::to($user->email)->send(new PasswordResetLink($resetUrl, $user->nom));

        return redirect()->back()->with('success', 'Un email de réinitialisation a été envoyé à ' . $user->email);
    }

    /**
     * Affiche le formulaire de réinitialisation
     */
    public function showResetForm(Request $request, string $token)
    {
        $email = $request->query('email');

        // Vérifier que le token existe et n'est pas expiré (60 minutes)
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', hash('sha256', $token))
            ->where('created_at', '>', now()->subMinutes(60))
            ->first();

        if (!$tokenRecord) {
            return redirect()->route('login')->withErrors([
                'email' => 'Ce lien de réinitialisation est invalide ou a expiré.',
            ]);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Réinitialise le mot de passe
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Vérifier le token
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', hash('sha256', $request->token))
            ->where('created_at', '>', now()->subMinutes(60))
            ->first();

        if (!$tokenRecord) {
            return redirect()->back()->withErrors([
                'email' => 'Ce lien de réinitialisation est invalide ou a expiré.',
            ]);
        }

        // Mettre à jour le mot de passe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors([
                'email' => 'Utilisateur non trouvé.',
            ]);
        }

        $user->password = $request->password;
        $user->save();

        // Supprimer le token utilisé
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');
    }
}
