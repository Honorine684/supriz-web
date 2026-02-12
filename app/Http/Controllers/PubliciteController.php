<?php

namespace App\Http\Controllers;
use App\Models\Publicite;
use Illuminate\Http\Request;

class PubliciteController extends Controller
{
    //
    public function publicite()
    {
        $publicite = Publicite::All();
        $active_link = "m";
        return view('publicite',compact("active_link","publicite"));
    }

    public function store(Request $request)
{
    // Validation des données
    $validatedData = $request->validate([
        'categorie_dhomme' => 'nullable|string', // Catégorie homme, femme, enfant
        'sousCategorieId' => 'nullable|integer', // Sous-catégorie sélectionnée
        'image' => 'required|image|mimes:jpeg,png,jpg,gif', // Image
        'lien' => 'nullable|url' // Lien optionnel
    ]);

    try {
        // Créer un nouveau produit (publicité)
        $publicite = new Publicite();
        $publicite->sous_categorie = $validatedData['sousCategorieId'];
        $publicite->lien = $request->input('lien');

        // Gestion de l'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $publicite->pathImage = $imagePath; // Enregistrement du chemin de l'image dans la BDD
        }

        // Sauvegarder le produit (publicité)
        $publicite->save();

        // Redirection avec un message de succès
        return redirect()->back()->with('success', 'Publicité créée avec succès');
    } catch (\Exception $e) {
        // En cas d'erreur, afficher un message d'erreur
        return redirect()->back()->with('error', 'Une erreur est survenue. Veuillez réessayer. Détails : ' . $e->getMessage());
    }
}



    public function deletePublicite($id)
{
    // Récupère l'élément par son id
    $publicite = Publicite::find($id);

    // Vérifie si l'élément existe
    if ($publicite) {
        $publicite->delete();  // Supprime l'élément
        return redirect()->back()->with('success', 'Publicite supprimé avec succès.');
    } else {
        return redirect()->back()->with('error', 'Publicite introuvable.');
    }
}

}