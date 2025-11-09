<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Auth;
class NotificationCompteController extends Controller
{
    //
    public function notificationCompte()
    {
        $users = User::withTrashed()->get();
        $active_link = "n";
        return view('notification-compte',compact("active_link","users"));
    }

    public function search(Request $request)
{
    $query = $request->input('query');  // Obtenir la requête de recherche

    if ($query) {
        // Rechercher les utilisateurs par nom ou prénom
        $users = User::where('nom', 'LIKE', "%{$query}%")
            ->orWhere('prenom', 'LIKE', "%{$query}%")
            ->get();
    } else {
        // Si aucune requête, on retourne tous les utilisateurs
        $users = User::all();
    }

    // Retourner une vue partielle avec les utilisateurs filtrés
    return view('partials.user-list', compact('users'))->render();
}

}