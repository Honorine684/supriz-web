<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function showLoginForm()
    {


        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Valider les données de connexion
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Définir les informations d'identification
        $credentials = $request->only('email', 'password');

        // Tenter de se connecter avec les informations d'identification fournies
        if (Auth::attempt($credentials)) {
            Auth::user()->update(['last_login_at' => Carbon::now()]);
            // Authentification réussie, rediriger vers la page d'accueil ou tableau de bord
            return redirect('/'); // Changez 'home' selon vos besoins
        }
        // Authentification échouée, rediriger vers le formulaire de connexion avec un message d'erreur
        return back()->withErrors([
            'email' => 'Les informations de connexion sont incorrectes.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Redirection vers la page d'accueil ou autre
    }
}