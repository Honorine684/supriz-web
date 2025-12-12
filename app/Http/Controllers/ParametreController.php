<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    //
    public function parametre()
    {
        $user = auth()->user(); // Récupère l'utilisateur connecté
        $active_link = "p";
        return view('parametre', compact("active_link", "user")); // Passer l'utilisateur à la vue
    }

    public function updateprofil(Request $request)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'profil_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de l'image
    ]);

    $user = Auth::user();

    // Mise à jour des informations
    $user->nom = $request->nom;
    $user->prenom = $request->prenom;
    $user->email = $request->email;

    // Gestion de l'image de profil
    if ($request->hasFile('profil_image')) {
        $imagePath = $request->file('profil_image')->store('profil_images', 'public'); // Sauvegarde dans le dossier public
        $user->profil_image = $imagePath;
    }

    $user->save();

    return redirect()->back()->with('success', 'Profil mis à jour avec succès.');
}


    
}
