<?php

namespace App\Http\Controllers;

use App\Models\Achat;
use App\Models\Commandes;
use Illuminate\Http\Request;

class NotificationVenteController extends Controller
{
    public function notificationVente()
{
    $commande_price = 0;

    // Récupère les commandes en cours avec les achats, les utilisateurs, et les produits (y compris ceux qui sont supprimés)
    $commandes_encours = Commandes::where('status', 'encours')
                        ->with(['achats.product' => function ($query) {
                            $query->withTrashed(); // Inclut les produits qui ont été supprimés
                        }, 'user'])
                        ->orderBy('created_at', 'desc')
                        ->get();

    $commandes_entente = Commandes::where('status', 'confirmer')
                        ->with(['achats.product' => function ($query) {
                            $query->withTrashed(); // Inclut les produits qui ont été supprimés
                        }, 'user'])
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
    $commandes_termine = Commandes::where('status', 'livrée')
                        ->with(['achats.product' => function ($query) {
                            $query->withTrashed(); // Inclut les produits qui ont été supprimés
                        }, 'user'])
                        ->orderBy('created_at', 'desc')
                        ->get();

                        $commande_price = []; // Initialisation du tableau des prix des commandes

                        foreach ($commandes_encours as $commande_encours) {
                            // Assurez-vous que le prix de la commande est initialisé à 0
                            if (!isset($commande_price[$commande_encours->id])) {
                                $commande_price[$commande_encours->id] = 0;
                            }
                            
                            foreach ($commande_encours->achats as $achat) {
                                // Vérification des prix promo et calcul du prix du produit avec la quantité
                                $prix_produit = $achat->product->prix_promo ? $achat->product->prix_promo : $achat->product->price;
                                $total_produit = $prix_produit * $achat->qte_achat;
                        
                                // Ajout des frais de livraison
                                $frais_livraison = $achat->product->prix_livraison;
                        
                                // Addition des frais de livraison et du prix du produit au prix total de la commande
                                $commande_price[$commande_encours->id] += $total_produit + $frais_livraison;
                            }
                        }
                        

    $active_link = "n"; // Variable utilisée dans la vue

    return view('notification-vente', compact('active_link', 'commandes_encours', 'commande_price', 'commandes_entente', 'commandes_termine')); // Envoi des données compactées à la vue
}



    public function closeShop(Request $request)
{
    // Validation des données
    $validatedData = $request->validate([
        'code' => 'required|string',
        'command_id' => 'required|integer',
    ]);

    // Récupère la commande en fonction de l'ID fourni
    $commande = Commandes::where('id', $request->command_id)->first();

    // Vérifie si la commande existe
    if (!$commande) {
        return redirect()->back()->with('error', "Cette commande P$request->command_id n'existe pas");
    }

    // Vérifie si le code de commande correspond
    if ($commande->code_commandes == $request->code) {
        // Met à jour le statut de la commande à "livrée"
        $commande->update(['status' => 'livrée']);

        // Redirection avec message de succès
        return redirect()->back()->with('success', "La commande a été mise à jour avec succès !");
    } else {
        // Si le code ne correspond pas, retour avec message d'erreur
        return redirect()->back()->with('error', "Le code $request->code de commande P$request->command_id est incorrect");
    }
}


    
}
