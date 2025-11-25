<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Musique;
use App\Models\SousCategorie;
use App\Models\Categorie;
use Illuminate\Support\Facades\Storage;

class MusiqueController extends Controller
{
    /**
     * Afficher la page de gestion des musiques
     */
    public function index()
    {
        // Récupérer les sous-catégories Supriz pour le formulaire
        $suprizCategorie = Categorie::where('name_id_categories', 's')->first();
        $sousCategories = SousCategorie::where('type_categorie_id', $suprizCategorie->id ?? null)->get();

        // Récupérer les musiques groupées par catégorie
        $musiquesGroupees = [];
        foreach ($sousCategories as $sousCategorie) {
            $musiques = Musique::where('sous_categorie_id', $sousCategorie->id)->get();
            if ($musiques->count() > 0) {
                $musiquesGroupees[$sousCategorie->name_sous_categories] = $musiques;
            }
        }

        $active_link = "musique";
        return view('musique', compact('musiquesGroupees', 'sousCategories', 'active_link'));
    }

    /**
     * Ajouter une nouvelle musique
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'sous_categorie_id' => 'required|exists:homme_sous_categories,id',
            'fichier_audio' => 'required|file|mimes:mp3,wav,ogg|max:5120', // Max 5MB
        ]);

        $audioPath = null;

        if ($request->hasFile('fichier_audio')) {
            $audioPath = $request->file('fichier_audio')->store('musiques', 'public');
        }

        Musique::create([
            'titre' => $request->titre,
            'fichier_audio' => $audioPath,
            'sous_categorie_id' => $request->sous_categorie_id,
            'duree' => $request->duree, // La durée sera envoyée depuis le client
            'is_active' => true,
            'is_predefined' => false,
        ]);

        return redirect()->route('musique')->with('success', 'Musique ajoutée avec succès');
    }

    /**
     * Mettre à jour une musique
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'sous_categorie_id' => 'required|exists:homme_sous_categories,id',
            'fichier_audio' => 'nullable|file|mimes:mp3,wav,ogg|max:5120',
        ]);

        $musique = Musique::find($id);

        if (!$musique) {
            return redirect()->back()->with('error', 'Musique non trouvée');
        }

        $audioPath = $musique->fichier_audio;
        $duree = $request->duree ?? $musique->duree;

        if ($request->hasFile('fichier_audio')) {
            // Supprimer l'ancien fichier
            if ($musique->fichier_audio && Storage::exists('public/' . $musique->fichier_audio)) {
                Storage::delete('public/' . $musique->fichier_audio);
            }

            $audioPath = $request->file('fichier_audio')->store('musiques', 'public');
        }

        $musique->update([
            'titre' => $request->titre,
            'fichier_audio' => $audioPath,
            'sous_categorie_id' => $request->sous_categorie_id,
            'duree' => $duree,
        ]);

        return redirect()->route('musique')->with('success', 'Musique mise à jour avec succès');
    }

    /**
     * Activer/Désactiver une musique
     */
    public function toggleStatus($id)
    {
        $musique = Musique::find($id);

        if (!$musique) {
            return redirect()->back()->with('error', 'Musique non trouvée');
        }

        $musique->is_active = !$musique->is_active;
        $musique->save();

        $status = $musique->is_active ? 'activée' : 'désactivée';
        return redirect()->route('musique')->with('success', "Musique {$status} avec succès");
    }

    /**
     * Supprimer une musique
     */
    public function destroy($id)
    {
        $musique = Musique::find($id);

        if (!$musique) {
            return redirect()->back()->with('error', 'Musique non trouvée');
        }

        // Supprimer le fichier audio
        if ($musique->fichier_audio && Storage::exists('public/' . $musique->fichier_audio)) {
            Storage::delete('public/' . $musique->fichier_audio);
        }

        $musique->delete();

        return redirect()->route('musique')->with('success', 'Musique supprimée avec succès');
    }

    /**
     * Récupérer les musiques d'une catégorie (AJAX)
     */
    public function getMusiquesByCategorie($categorieId)
    {
        $musiques = Musique::where('sous_categorie_id', $categorieId)
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'musiques' => $musiques,
            'has_musiques' => $musiques->count() > 0
        ]);
    }
}
