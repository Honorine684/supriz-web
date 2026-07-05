<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // Import correct du contrôleur parent
use App\Models\Historique; // Vérifier le modèle
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiHistoriqueController extends Controller
{
    public function index()
    {
        // Récupérer l'ID de l'utilisateur connecté
        $userId = Auth::id();

        // Vérifier si l'utilisateur est bien connecté
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Récupérer les historiques de l'utilisateur (avec la commande + ses
        // achats/produits pour connaître l'heure de récupération et savoir si
        // la commande est personnalisable).
        $historiques = Historique::where('users_id', $userId)
            ->with('commande.achats.product')
            ->orderBy('created_at', 'desc')
            ->get();

        // Mapper les données pour correspondre à la structure souhaitée
        $formattedHistoriques = $historiques->map(function ($historique) {
            $commande = $historique->commande;

            $dateLivraison = null;
            $heureLivraison = null;
            $isPersonnalisable = false;

            if ($commande) {
                $dateLivraison = $commande->date_livraison
                    ? $commande->date_livraison->format('Y-m-d')
                    : null;
                // La colonne `time` renvoie "HH:MM:SS" : on normalise en "HH:MM".
                $heureLivraison = $commande->heure_livraison
                    ? \Carbon\Carbon::parse($commande->heure_livraison)->format('H:i')
                    : null;
                // Commande personnalisable si au moins un produit l'est.
                $isPersonnalisable = $commande->achats->contains(function ($achat) {
                    return optional($achat->product)->personnalisable === 'yes';
                });
            }

            return [
                'description' => $historique->description,
                'prix' => $historique->prix,
                'code_historiques' => $historique->code_historiques,
                'created_at' => $historique->created_at->toISOString(), // Formater la date
                // Heure de récupération (renseignée pour les commandes personnalisables).
                'date_livraison' => $dateLivraison,
                'heure_livraison' => $heureLivraison,
                'is_personnalisable' => $isPersonnalisable,
            ];
        });

        // Retourner les données au format JSON
        return response()->json($formattedHistoriques);
    }
}
