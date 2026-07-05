<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SousCategorie;
use App\Models\Categorie;
use App\Models\Product;
use App\Models\Marchand;
use App\Models\Badge;
use Illuminate\Support\Facades\Storage;

class PublierArticlesController extends Controller
{
    //
    public function publierArticles()
    {
        $souscategorie = SousCategorie::All();
        $produits = Product::latest()->take(7)->get();
        $marchands = Marchand::orderBy('nom')->get();
        $badges = Badge::orderBy('name')->get();
        $active_link = "s";
        return view('article',compact("active_link","souscategorie","produits","marchands","badges"));
    }

    public function getSousCategories(Request $request)
{
    // Récupérer la catégorie à partir du nom ou de l'ID envoyé depuis le front-end
    $categorie = Categorie::where('name_id_categories', $request->input('categorie'))->first();

    // Vérifier si la catégorie existe
    if (!$categorie) {
        // Si la catégorie n'existe pas, renvoyer une réponse vide ou un message d'erreur
        return response()->json(['error' => 'Catégorie non trouvée'], 404);
    }

    // Récupérer les sous-catégories associées à cette catégorie
    $souscategories = SousCategorie::where('type_categorie_id', $categorie->id)->get();

    // Vérifier s'il y a des sous-catégories
    if ($souscategories->isEmpty()) {
        return response()->json([]); // Renvoyer une liste vide si aucune sous-catégorie n'est trouvée
    }

    // Construire un tableau des sous-catégories avec leurs IDs
    $sousCategories = $souscategories->map(function($souscategorie) {
        return [
            'id' => $souscategorie->id,
            'name' => $souscategorie->name_sous_categories
        ];
    });

    // Retourner la liste des sous-catégories au format JSON
    return response()->json($sousCategories);
}


public function store(Request $request)
{
    // Ajout d'une valeur par défaut pour la personnalisation si la case n'est pas cochée
    $request->merge([
        'personnalisation' => $request->has('personnalisation') ? $request->input('personnalisation') : 'off'
    ]);

    // Validation des données
    $validatedData = $request->validate([
        'name_product' => 'required|string|max:255',
        'prix_product' => 'required|numeric',
        'marchand_id' => 'required|integer|exists:marchands,id',
        'info' => 'required|integer|min:1|max:8760',
        'description' => 'required|string',
        'prix_promotion' => 'nullable|numeric',
        'prix_livraison' => 'nullable|numeric',
        'personnalisation' => 'nullable|string',
        'categorie_dhomme' => 'required|string|in:h,f,e',
        'evaluation' => 'nullable|integer|min:1|max:5',
        'img1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'img2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'sousCategorieId' => 'required|integer',
        'badge_ids' => 'nullable|array|max:5',
        'badge_ids.*' => 'integer|exists:badges,id',
    ], $this->validationMessages(), $this->validationAttributes());

    try {
        // Création de l'article
        $article = new Product();
        $article->title = $validatedData['name_product'];
        $article->price = $validatedData['prix_product'];
        $article->description = $validatedData['description'];
        $marchand = Marchand::findOrFail($validatedData['marchand_id']);
        $article->marchand_id = $marchand->id;
        $article->entreprise = $marchand->nom;
        // Colonnes NOT NULL en base : évaluation et prix_livraison ne doivent
        // jamais être null (0 par défaut). Le champ prix_livraison a été retiré
        // du formulaire (calcul auto), d'où le fallback.
        $article->evaluation = $validatedData['evaluation'] ?? 0;
        $article->prix_promo = $validatedData['prix_promotion'] ?? null;
        $article->prix_livraison = $validatedData['prix_livraison'] ?? 0;
        $article->info = $validatedData['info'];
        $article->statut = 'in_stock';

        // Gestion de la personnalisation (présente ou non)
        if ($validatedData['personnalisation'] == 'on') {
            $article->personnalisable = "yes";
        } else {
            $article->personnalisable = "no";
        }

        // Gestion des images
        if ($request->hasFile('img1')) {
            $article->photo_main = $request->file('img1')->store('images', 'public');
        }

        if ($request->hasFile('img2')) {
            $article->photo_secondary = $request->file('img2')->store('images', 'public');
        }

        // Sauvegarde de l'article dans la base de données
        $article->save();

        // Ajout de la sous-catégorie au produit
        $article->sousCategories()->attach($validatedData['sousCategorieId']);

        // Attacher les badges (max 5)
        if (!empty($validatedData['badge_ids'])) {
            $article->badges()->sync(array_slice($validatedData['badge_ids'], 0, 5));
        }

        // Rediriger avec un message de succès
        return redirect()->back()->with('success', 'Article publié avec succès');
    } catch (\Exception $e) {
        report($e); // loggue l'exception réelle dans storage/logs/laravel.log
        return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
    }
}

public function edit($id)
{
    $article = Product::with('badges')->findOrFail($id);
    $product = Product::with('sousCategories.categorie')->findOrFail($id);
    $firstCategory = $product->sousCategories->first()->categorie ?? null;
    $sousCategories = $product->sousCategories;

    $categorie = $product->sousCategories->first()->categorie ?? null;

    if ($categorie) {
        // Obtenir toutes les sous-catégories de cette catégorie
        $allSousCategories = $categorie->sousCategories;

        // Filtrer les sous-catégories qui ne sont pas associées au produit
        $sousCategoriesNonAssociees = $allSousCategories->reject(function ($sousCategorie) use ($product) {
            return $product->sousCategories->contains($sousCategorie);
        });
    } else {
        $sousCategoriesNonAssociees = collect();
    }


    $active_link = "s";
    $produits = Product::latest()->take(7)->get();
    $marchands = Marchand::orderBy('nom')->get();
    $badges = Badge::orderBy('name')->get();
    return view('article', compact('article','active_link','produits','firstCategory','sousCategories','sousCategoriesNonAssociees','marchands','badges'));
}

public function update(Request $request, $id)
{
    // Ajout d'une valeur par défaut pour la personnalisation si la case n'est pas cochée
    $request->merge([
        'personnalisation' => $request->has('personnalisation') ? $request->input('personnalisation') : 'off'
    ]);

    // Validation des données
    $validatedData = $request->validate([
        'name_product' => 'required|string|max:255',
        'prix_product' => 'required|numeric',
        'marchand_id' => 'required|integer|exists:marchands,id',
        'info' => 'required|integer|min:1|max:8760',
        'description' => 'required|string',
        'prix_promotion' => 'nullable|numeric',
        'prix_livraison' => 'nullable|numeric',
        'personnalisation' => 'nullable|string',
        'categorie_dhomme' => 'required|string|in:h,f,e',
        'evaluation' => 'nullable|integer|min:1|max:5',
        'img1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'img2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'sousCategorieId' => 'required|integer',
        'badge_ids' => 'nullable|array|max:5',
        'badge_ids.*' => 'integer|exists:badges,id',
    ], $this->validationMessages(), $this->validationAttributes());

    try {
        // Recherche de l'article existant
        $article = Product::findOrFail($id);

        // Mise à jour des informations de l'article
        $article->title = $validatedData['name_product'];
        $article->price = $validatedData['prix_product'];
        $article->description = $validatedData['description'];
        $marchand = Marchand::findOrFail($validatedData['marchand_id']);
        $article->marchand_id = $marchand->id;
        $article->entreprise = $marchand->nom;
        $article->evaluation = $validatedData['evaluation'] ?? $article->evaluation;
        $article->prix_promo = $validatedData['prix_promotion'] ?? $article->prix_promo;
        $article->prix_livraison = $validatedData['prix_livraison'] ?? $article->prix_livraison;
        $article->info = $validatedData['info'];


        // Gestion de la personnalisation (présente ou non)
        if ($validatedData['personnalisation'] == 'on') {
            $article->personnalisable = "yes";
        } else {
            $article->personnalisable = "no";
        }
        // Gestion des images (mise à jour uniquement si un nouveau fichier est fourni)
        if ($request->hasFile('img1')) {
            // Supprimer l'ancienne image si nécessaire
            if ($article->photo_main) {
                Storage::disk('public')->delete($article->photo_main);
            }
            $article->photo_main = $request->file('img1')->store('images', 'public');
        }

        if ($request->hasFile('img2')) {
            // Supprimer l'ancienne image si nécessaire
            if ($article->photo_secondary) {
                Storage::disk('public')->delete($article->photo_secondary);
            }
            $article->photo_secondary = $request->file('img2')->store('images', 'public');
        }

        // Sauvegarde de l'article dans la base de données
        $article->save();

        // Mise à jour de la sous-catégorie du produit
        $article->sousCategories()->sync([$validatedData['sousCategorieId']]);

        // Synchroniser les badges (max 5)
        $article->badges()->sync(
            isset($validatedData['badge_ids'])
                ? array_slice($validatedData['badge_ids'], 0, 5)
                : []
        );

        $souscategorie = SousCategorie::All();
        $produits = Product::latest()->take(7)->get();
        $active_link = "s";
        // Rediriger avec un message de succès
        return redirect()->route('admin.publierArticles')  // Remplacez 'article.index' par le nom de votre route de redirection
        ->with('success', 'Modification réussie !');
        } catch (\Exception $e) {
        report($e); // loggue l'exception réelle dans storage/logs/laravel.log
        return redirect()->back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
    }
}





    /** Attributs (noms de champ) en français pour les messages d'erreur. */
    private function validationAttributes(): array
    {
        return [
            'name_product' => 'nom du produit',
            'prix_product' => 'prix',
            'marchand_id' => 'marchand',
            'info' => 'durée de préparation',
            'description' => 'description',
            'prix_promotion' => 'prix promotionnel',
            'prix_livraison' => 'prix de livraison',
            'personnalisation' => 'personnalisation',
            'categorie_dhomme' => 'catégorie',
            'evaluation' => 'évaluation',
            'img1' => 'image principale',
            'img2' => 'image secondaire',
            'sousCategorieId' => 'sous-catégorie',
            'badge_ids' => 'badges',
            'badge_ids.*' => 'badge',
        ];
    }

    /** Messages de validation personnalisés en français. */
    private function validationMessages(): array
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être du texte.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
            'integer' => 'Le champ :attribute doit être un entier.',
            'max' => [
                'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
                'file' => "Le fichier :attribute ne doit pas dépasser :max Ko.",
                'array' => 'Le champ :attribute ne doit pas contenir plus de :max éléments.',
                'numeric' => 'Le champ :attribute ne doit pas être supérieur à :max.',
            ],
            'min' => [
                'numeric' => 'Le champ :attribute doit être au moins :min.',
                'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
            ],
            'in' => 'La valeur du champ :attribute est invalide.',
            'exists' => "Le :attribute sélectionné n'existe pas.",
            'image' => 'Le champ :attribute doit être une image.',
            'mimes' => "Le fichier :attribute doit être de type: :values.",
            'array' => 'Le champ :attribute doit être une liste.',
        ];
    }
}