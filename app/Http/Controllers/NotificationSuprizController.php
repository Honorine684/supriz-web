<?php

namespace App\Http\Controllers;

use App\Models\Supriz;
use Illuminate\Http\Request;

class NotificationSuprizController extends Controller
{
    public function notificationSupriz()
    {
        // Récupère les supriz en cours avec les utilisateurs associés
        $supriz_encours = Supriz::where('status', 'encours')
            ->with('user') // Charge les utilisateurs associés
            ->get();

        // Récupère les supriz livrés avec les utilisateurs associés
        $supriz_termine = Supriz::where('status', 'livrée')
            ->with('user') // Charge les utilisateurs associés
            ->get();

        // Calcule le prix total des supriz en cours
        $commande_price_encours = $supriz_encours->sum(function($supriz) {
            return (int) str_replace(',', '', $supriz->prix); // Convertit le prix en nombre pour la somme
        });

        // Calcule le prix total des supriz livrés
        $commande_price_termine = $supriz_termine->sum(function($supriz) {
            return (int) str_replace(',', '', $supriz->prix); // Convertit le prix en nombre pour la somme
        });

        $active_link = "n"; // Variable utilisée dans la vue

        // Retourne la vue avec les données compactées
        return view('notification-supriz', compact('active_link', 'supriz_encours', 'commande_price_encours', 'supriz_termine', 'commande_price_termine'));
    }

    public function closeSupriz(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'supriz_id' => 'required|integer',
        ]);
    
        // Récupère la commande en fonction de l'ID fourni
        $supriz = Supriz::where('id', $request->supriz_id)->first();
    
        // Vérifie si la commande existe
        if (!$supriz) {
            return redirect()->back()->with('error', "Ce message supriz S$request->supriz_id n'existe pas");
        }
    
        $supriz->update(['status' => 'livrée']);
    
        // Redirection avec message de succès
        return redirect()->back()->with('success', "La commande a été mise à jour avec succès !");
        
    }
}
