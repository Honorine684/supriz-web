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
        // Récupérer la catégorie Supriz
        $suprizCategorie = Categorie::where('name_id_categories', 's')->first();

        // Récupérer les sous-catégories Supriz (avec pagination)
        // orderBy obligatoire : sans tri stable, MySQL peut répéter/omettre
        // des lignes d'une page à l'autre.
        $sousCategoriesSupriz = SousCategorie::with('categorie')
            ->where('type_categorie_id', $suprizCategorie->id ?? null)
            ->orderBy('id')
            ->paginate(5, ['*'], 'supriz_page');

        // Récupérer les sous-catégories Homme/Femme/Enfant (avec pagination)
        $sousCategoriesAutres = SousCategorie::with('categorie')
            ->where('type_categorie_id', '!=', $suprizCategorie->id ?? 0)
            ->orderBy('id')
            ->paginate(5, ['*'], 'autres_page');

        // Pour compatibilité avec l'ancien code (toutes les sous-catégories)
        $sousCategories = SousCategorie::with('categorie')->orderBy('id')->paginate(5);

        // Récupérer les catégories Supriz prédéfinies
        $categoriesSuprizPredefinies = ['Anniversaire', 'Mariage', 'Vœux', 'Nouvel an', 'Noël'];

        $suprizPredefinies = SousCategorie::where('type_categorie_id', $suprizCategorie->id ?? null)
            ->whereIn('name_sous_categories', $categoriesSuprizPredefinies)
            ->get();

        // Récupérer les catégories Supriz personnalisées (ajoutées manuellement)
        $suprizPersonnalisees = SousCategorie::where('type_categorie_id', $suprizCategorie->id ?? null)
            ->whereNotIn('name_sous_categories', $categoriesSuprizPredefinies)
            ->get();

        $active_link = "ca";
        return view('categorie', compact("active_link", "sousCategories", "sousCategoriesSupriz", "sousCategoriesAutres", "suprizPredefinies", "suprizPersonnalisees"));
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
            'placeholder_texte' => 'nullable|string|max:100',
            'prix' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'icone' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

        // Gérer l'upload de l'image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('sous-categories', 'public');
        }

        // Gérer l'upload de l'icône
        $iconePath = null;
        if ($request->hasFile('icone')) {
            $iconePath = $request->file('icone')->store('icones-categories', 'public');
        }

        // Créer une nouvelle sous-catégorie
        SousCategorie::create([
            'type_categorie_id' => $categorie->id,
            'name_sous_categories' => $request->sousCategorie,
            'placeholder_texte' => $request->placeholder_texte,
            'prix' => $request->prix,
            'image' => $imagePath,
            'icone' => $iconePath,
        ]);

        return redirect()->route('admin.categorie')->with('success', 'Sous-catégorie ajoutée avec succès');
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

    // Récupérer la catégorie Supriz
    $suprizCategorie = Categorie::where('name_id_categories', 's')->first();

    // Récupérer les sous-catégories Supriz (avec pagination)
    // orderBy obligatoire : sans tri stable, MySQL peut répéter/omettre
    // des lignes d'une page à l'autre.
    $sousCategoriesSupriz = SousCategorie::with('categorie')
        ->where('type_categorie_id', $suprizCategorie->id ?? null)
        ->orderBy('id')
        ->paginate(5, ['*'], 'supriz_page');

    // Récupérer les sous-catégories Homme/Femme/Enfant (avec pagination)
    $sousCategoriesAutres = SousCategorie::with('categorie')
        ->where('type_categorie_id', '!=', $suprizCategorie->id ?? 0)
        ->orderBy('id')
        ->paginate(5, ['*'], 'autres_page');

    // Pour compatibilité avec l'ancien code
    $sousCategories = SousCategorie::with('categorie')->orderBy('id')->paginate(5);

    // Récupérer les catégories Supriz prédéfinies
    $categoriesSuprizPredefinies = ['Anniversaire', 'Mariage', 'Vœux', 'Nouvel an', 'Noël'];

    $suprizPredefinies = SousCategorie::where('type_categorie_id', $suprizCategorie->id ?? null)
        ->whereIn('name_sous_categories', $categoriesSuprizPredefinies)
        ->get();

    // Récupérer les catégories Supriz personnalisées (ajoutées manuellement)
    $suprizPersonnalisees = SousCategorie::where('type_categorie_id', $suprizCategorie->id ?? null)
        ->whereNotIn('name_sous_categories', $categoriesSuprizPredefinies)
        ->get();

    $active_link = "ca";

    return view('categorie', compact('sousCategorie', 'sousCategories', 'sousCategoriesSupriz', 'sousCategoriesAutres', 'suprizPredefinies', 'suprizPersonnalisees', 'active_link'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'categorie' => 'required|in:h,f,e,s',
        'sousCategorie' => 'required|string|max:255',
        'placeholder_texte' => 'nullable|string|max:100',
        'prix' => 'nullable|numeric|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        'icone' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Recherche de la sous-catégorie à modifier
    $sousCategorie = SousCategorie::find($id);
    $categorie = Categorie::where('name_id_categories', $request->categorie)->first();

    if (!$categorie || !$sousCategorie) {
        return redirect()->back()->with('error', 'Catégorie ou Sous-catégorie non trouvée');
    }

    // Gérer l'upload de l'image
    $imagePath = $sousCategorie->image;
    if ($request->hasFile('image')) {
        // Supprimer l'ancienne image si elle existe
        if ($sousCategorie->image && Storage::exists('public/' . $sousCategorie->image)) {
            Storage::delete('public/' . $sousCategorie->image);
        }
        $imagePath = $request->file('image')->store('sous-categories', 'public');
    }

    // Gérer l'upload de l'icône
    $iconePath = $sousCategorie->icone;
    if ($request->hasFile('icone')) {
        // Supprimer l'ancienne icône si elle existe
        if ($sousCategorie->icone && Storage::exists('public/' . $sousCategorie->icone)) {
            Storage::delete('public/' . $sousCategorie->icone);
        }
        $iconePath = $request->file('icone')->store('icones-categories', 'public');
    }

    // Mise à jour de la sous-catégorie
    $sousCategorie->update([
        'type_categorie_id' => $categorie->id,
        'name_sous_categories' => $request->sousCategorie,
        'placeholder_texte' => $request->placeholder_texte,
        'prix' => $request->prix,
        'image' => $imagePath,
        'icone' => $iconePath,
    ]);

    return redirect()->route('admin.categorie')->with('success', 'Sous-catégorie mise à jour avec succès');
}

public function toggleStatus($id)
{
    $sousCategorie = SousCategorie::find($id);

    if (!$sousCategorie) {
        return redirect()->back()->with('error', 'Sous-catégorie non trouvée');
    }

    $sousCategorie->is_active = !$sousCategorie->is_active;
    $sousCategorie->save();

    $status = $sousCategorie->is_active ? 'activée' : 'désactivée';
    return redirect()->route('admin.categorie')->with('success', "Sous-catégorie {$status} avec succès");
}

public function updateSuprizImage(Request $request, $id)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
    ]);

    $sousCategorie = SousCategorie::find($id);

    if (!$sousCategorie) {
        return redirect()->back()->with('error', 'Sous-catégorie non trouvée');
    }

    // Supprimer l'ancienne image si elle existe
    if ($sousCategorie->image && Storage::exists('public/' . $sousCategorie->image)) {
        Storage::delete('public/' . $sousCategorie->image);
    }

    // Uploader la nouvelle image
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('sous-categories', 'public');
        $sousCategorie->image = $imagePath;
        $sousCategorie->save();
    }

    return redirect()->route('admin.categorie')->with('success', 'Image mise à jour avec succès');
}

public function updateSuprizIcone(Request $request, $id)
{
    $request->validate([
        'icone' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $sousCategorie = SousCategorie::find($id);

    if (!$sousCategorie) {
        return redirect()->back()->with('error', 'Sous-catégorie non trouvée');
    }

    // Supprimer l'ancienne icône si elle existe
    if ($sousCategorie->icone && Storage::exists('public/' . $sousCategorie->icone)) {
        Storage::delete('public/' . $sousCategorie->icone);
    }

    // Uploader la nouvelle icône
    if ($request->hasFile('icone')) {
        $iconePath = $request->file('icone')->store('icones-categories', 'public');
        $sousCategorie->icone = $iconePath;
        $sousCategorie->save();
    }

    return redirect()->route('admin.categorie')->with('success', 'Icône mise à jour avec succès');
}

public function updateSuprizGiftImage(Request $request, $id)
{
    $request->validate([
        'gift_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
    ]);

    $sousCategorie = SousCategorie::find($id);

    if (!$sousCategorie) {
        return redirect()->back()->with('error', 'Sous-catégorie non trouvée');
    }

    // Supprimer l'ancienne image cadeau si elle existe
    if ($sousCategorie->gift_image && Storage::exists('public/' . $sousCategorie->gift_image)) {
        Storage::delete('public/' . $sousCategorie->gift_image);
    }

    // Uploader la nouvelle image cadeau
    if ($request->hasFile('gift_image')) {
        $giftImagePath = $request->file('gift_image')->store('gift-images', 'public');
        $sousCategorie->gift_image = $giftImagePath;
        $sousCategorie->save();
    }

    return redirect()->route('admin.categorie')->with('success', 'Image cadeau mise à jour avec succès');
}

}
