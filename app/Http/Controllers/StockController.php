<?php

namespace App\Http\Controllers;
use App\Models\Achat;
use App\Models\Commandes;
use Illuminate\Http\Request;
use App\Models\Product;

class StockController extends Controller
{
    //
    public function stocks()
{
    $achats = 0;
    $montantEncaisse = 0; // Initialisation de la variable

    // Récupérer toutes les commandes avec le statut 'livrée'
    $commandes = Commandes::where('status', 'livrée')->get();

    // Parcourir chaque commande et additionner les quantités achetées et le montant encaissé
    foreach ($commandes as $commande) {
        $achats += Achat::where('commande_id', $commande->id)->sum('qte_achat');
        $montantEncaisse += ($commande->prix_total_commande);
    }

    // Nombre de produits
    $produits = Product::all();
    $nbrproduits = $produits->count();

    // Variable pour activer le lien dans la vue
    $active_link = 's';

    // Retourner la vue avec les données des achats, produits et montant encaissé
    return view('stock', compact('active_link', 'achats', 'nbrproduits', 'montantEncaisse', 'produits'));
}


public function changerStatut(Request $request)
{
    // Valider les données entrantes
    $request->validate([
        'produit_id' => 'required|exists:products,id',
        'statut' => 'required|in:in_stock,unavailable',
    ]);

    // Récupérer le produit
    $produit = Product::find($request->produit_id);

    // Mettre à jour le statut
    $produit->statut = $request->statut;  // Assurez-vous que la colonne 'statut' existe dans la table products
    $produit->save();

    return response()->json(['message' => 'Statut mis à jour avec succès']);
}

public function destroyProduit($id)
{
    // Récupère l'élément par son id
    $produit = Product::find($id);

    // Vérifie si l'élément existe
    if ($produit) {
        $produit->delete();  // Supprime l'élément
        return redirect()->back()->with('success', 'Produit supprimé avec succès.');
    } else {
        return redirect()->back()->with('error', 'Produit introuvable.');
    }
}


}