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
                        }, 'achats.product.marchand', 'user'])
                        ->orderBy('created_at', 'desc')
                        ->get();

    // Auto-nettoyage : un panier « en cours » dont le marchand a été supprimé
    // (ou dont tous les produits ont été supprimés) ne pourra jamais être
    // finalisé. Il encombre l'admin et reste coincé dans le panier du client.
    // On le purge ici. Un panier est vivant s'il a au moins une ligne dont le
    // produit existe encore ET dont le marchand existe encore (relation null
    // si le marchand est en soft-delete).
    $commandes_mortes = $commandes_encours->filter(function ($commande) {
        if ($commande->achats->isEmpty()) {
            return false; // laissé aux autres nettoyages (panier fraîchement créé)
        }
        $vivante = $commande->achats->contains(function ($achat) {
            return $achat->product
                && !$achat->product->trashed()
                && $achat->product->marchand;
        });
        return !$vivante;
    });

    if ($commandes_mortes->isNotEmpty()) {
        $idsMortes = $commandes_mortes->pluck('id');
        Achat::whereIn('commande_id', $idsMortes)->delete();
        Commandes::whereIn('id', $idsMortes)->delete();
        $commandes_encours = $commandes_encours->reject(function ($commande) use ($idsMortes) {
            return $idsMortes->contains($commande->id);
        });
    }

    $commandes_entente = Commandes::where('status', 'confirmer')
                        ->with(['achats.product' => function ($query) {
                            $query->withTrashed(); // Inclut les produits qui ont été supprimés
                        }, 'achats.product.marchand', 'user'])
                        ->orderBy('created_at', 'desc')
                        ->get();

    $commandes_termine = Commandes::where('status', 'livrée')
                        ->with(['achats.product' => function ($query) {
                            $query->withTrashed(); // Inclut les produits qui ont été supprimés
                        }, 'achats.product.marchand', 'user'])
                        ->orderBy('created_at', 'desc')
                        ->get();

                        $commande_price = []; // Initialisation du tableau des prix des commandes

                        foreach ($commandes_encours as $commande_encours) {
                            // Assurez-vous que le prix de la commande est initialisé à 0
                            if (!isset($commande_price[$commande_encours->id])) {
                                $commande_price[$commande_encours->id] = 0;
                            }

                            foreach ($commande_encours->achats as $achat) {
                                // Produit potentiellement supprimé définitivement (hors soft-delete) :
                                // on l'ignore pour éviter un accès sur null.
                                $produit = $achat->product;
                                if (!$produit) {
                                    continue;
                                }

                                // Vérification des prix promo et calcul du prix du produit avec la quantité
                                $prix_produit = $produit->prix_promo ? $produit->prix_promo : $produit->price;
                                $total_produit = $prix_produit * $achat->qte_achat;

                                // Ajout des frais de livraison (0 si non défini)
                                $frais_livraison = $produit->prix_livraison ?? 0;

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
