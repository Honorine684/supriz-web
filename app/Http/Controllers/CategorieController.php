<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SousCategorie;
use App\Models\Categorie;
use Illuminate\Support\Facades\Storage;

class CategorieController extends Controller
{
    public function categorie()
    {
         // Récupérer toutes les sous-catégories avec leur catégorie associée
        $sousCategories = SousCategorie::with('categorie')->get();
        // Récupérer toutes les catégories principales
        $categories = Categorie::all();
        $active_link = "ca";
        return view('categorie', compact("active_link","sousCategories","categories"));
    }

    /**
     * Gérer la requête d'ajout de sous-catégorie.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation des champs du formulaire
        $request->validate([
            'categorie' => 'required|in:h,f,e,s',
            'sousCategorie' => 'required|string|max:255',
            'prix' => 'nullable|numeric|min:0',
        ]);

        // Recherche de la catégorie par 'name_id_categories'
        $categorie = Categorie::where('name_id_categories', $request->categorie)->first();

        if (!$categorie) {
            return redirect()->back()->with('error', 'Catégorie non trouvée');
        }

        // Vérification de l'existence de la sous-catégorie
        $Souscategorie = SousCategorie::where('type_categorie_id', $categorie->id)
            ->where('name_sous_categories', $request->sousCategorie)
            ->first();

        if ($Souscategorie) {
            return redirect()->back()->with('error', 'Sous-catégorie existe déjà !');
        }

        // Créer une nouvelle sous-catégorie
        SousCategorie::create([
            'type_categorie_id' => $categorie->id,
            'name_sous_categories' => $request->sousCategorie,
            'prix' => $request->prix,
        ]);

        return redirect()->route('categorie')->with('success', 'Sous-catégorie ajoutée avec succès');
    }

    public function deleteCategorie($id)
{
    // Récupère l'élément par son id
    $categorie = SousCategorie::find($id);

    // Vérifie si l'élément existe
    if ($categorie) {
        $categorie->delete();  // Supprime l'élément
        return redirect()->back()->with('success', 'Categorie supprimé avec succès.');
    } else {
        return redirect()->back()->with('error', 'Categorie introuvable.');
    }
}

public function edit($id)
{
    $sousCategorie = SousCategorie::find($id);
    $sousCategories = SousCategorie::with('categorie')->get(); // Pour la liste des catégories existantes
    $active_link = "ca";

    return view('categorie', compact('sousCategorie', 'sousCategories', 'active_link'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'categorie' => 'required|in:h,f,e,s',
        'sousCategorie' => 'required|string|max:255',
        'prix' => 'nullable|numeric|min:0',
    ]);

    // Recherche de la sous-catégorie à modifier
    $sousCategorie = SousCategorie::find($id);
    $categorie = Categorie::where('name_id_categories', $request->categorie)->first();

    if (!$categorie || !$sousCategorie) {
        return redirect()->back()->with('error', 'Catégorie ou Sous-catégorie non trouvée');
    }

    // Mise à jour de la sous-catégorie
    $sousCategorie->update([
        'type_categorie_id' => $categorie->id,
        'name_sous_categories' => $request->sousCategorie,
        'prix' => $request->prix,
    ]);

    return redirect()->route('categorie')->with('success', 'Sous-catégorie mise à jour avec succès');
}

/**
 * Mettre à jour l'image d'une catégorie principale
 */
public function updateCategoryImage(Request $request, $id)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
    ]);

    $categorie = Categorie::find($id);

    if (!$categorie) {
        return redirect()->back()->with('error', 'Catégorie non trouvée');
    }

    // Supprimer l'ancienne image si elle existe
    if ($categorie->image && Storage::exists('public/' . $categorie->image)) {
        Storage::delete('public/' . $categorie->image);
    }

    // Uploader la nouvelle image
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('categories', 'public');
        $categorie->image = $imagePath;
        $categorie->save();
    }

    return redirect()->route('categorie')->with('success', 'Image de la catégorie mise à jour avec succès');
}

}
