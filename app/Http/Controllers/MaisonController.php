<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\SousCategorie;
use App\Models\Categorie;
use App\Models\Commandes;
use App\Models\Product;
use App\Models\Achat;
use Carbon\Carbon;

class MaisonController extends Controller
{
    public function maison()
    {
        // Définir la variable active_link
        $active_link = "maison";

        // ============ STATISTIQUES SUPRIZ PAR CATÉGORIE ============
        // Récupérer dynamiquement les catégories Supriz actives depuis la base de données
        $suprizCategorie = Categorie::where('name_id_categories', 's')->first();
        $categoriesFromDb = SousCategorie::where('type_categorie_id', $suprizCategorie->id ?? null)
            ->where('is_active', true)
            ->pluck('name_sous_categories')
            ->toArray();

        $categories = !empty($categoriesFromDb) ? $categoriesFromDb : ['Mariage', 'Vœux', 'Anniversaire', 'Nouvel an', 'Noël'];
        $statsSupriz = [];

        foreach ($categories as $categorie) {
            // Récupérer l'ID de la sous-catégorie
            $sousCategorie = SousCategorie::where('name_sous_categories', $categorie)->first();

            if (!$sousCategorie) {
                $statsSupriz[$categorie] = [
                    'total' => 0,
                    'variation' => 0
                ];
                continue;
            }

            // Messages du mois en cours
            $currentMonth = Message::where('sous_categorie_id', $sousCategorie->id)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();

            // Messages du mois précédent
            $previousMonth = Message::where('sous_categorie_id', $sousCategorie->id)
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->count();

            // Calculer la variation
            $variation = $currentMonth - $previousMonth;

            $statsSupriz[$categorie] = [
                'total' => $currentMonth,
                'variation' => $variation
            ];
        }

        // ============ STATISTIQUES GÉNÉRALES ============
        // Nombre total d'abonnés (utilisateurs)
        $nombresAbonnes = User::count();

        // Nombre de clics (nombre total de messages créés ce mois)
        $nombreClics = Message::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Clients actifs (nombre de messages actifs ce mois)
        $clientsActifs = Message::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('is_active', true)
            ->count();

        // ============ ACTIVITÉ MENSUELLE DU SITE ============
        $monthlyActivity = Message::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $activityData = [
            'months' => [],
            'totals' => []
        ];

        $monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                       'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

        for ($i = 1; $i <= 12; $i++) {
            $activityData['months'][] = $monthNames[$i - 1];
            $activityData['totals'][] = $monthlyActivity->get($i)->total ?? 0;
        }

        // ============ MESSAGES RÉCENTS ============
        $utilisateursRecents = Message::with('sousCategorie')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($message) {
                return [
                    'id' => $message->id,
                    'nom' => substr($message->titre, 0, 20) . '...',
                    'avatar' => null,
                    'categorie' => $message->sousCategorie->name_sous_categories ?? 'Catégorie',
                    'date' => $message->created_at->format('d/m/Y')
                ];
            });

        // ============ STATS E-COMMERCE ============
        $totalVentes = Commandes::whereIn('status', ['confirmer', 'livrée'])
            ->sum('prix_total_commande');
        $totalLivraison = Commandes::whereIn('status', ['confirmer', 'livrée'])
            ->sum('prix_total_livraison');
        $countEncours = Commandes::where('status', 'encours')->count();
        $countConfirmer = Commandes::where('status', 'confirmer')->count();
        $countLivree = Commandes::where('status', 'livrée')->count();
        $nbProduits = Product::count();
        $nbAchatsTotal = Achat::sum('qte_achat');

        // Ventes par mois (année en cours)
        $ventesMonthly = Commandes::whereIn('status', ['confirmer', 'livrée'])
            ->whereYear('created_at', Carbon::now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(prix_total_commande) as total')
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $ecomActivity = ['months' => [], 'totals' => []];
        for ($i = 1; $i <= 12; $i++) {
            $ecomActivity['months'][] = $monthNames[$i - 1];
            $ecomActivity['totals'][] = (float) ($ventesMonthly->get($i)->total ?? 0);
        }

        // Commandes récentes
        $commandesRecentes = Commandes::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($c) {
                $clientName = 'Anonyme';
                if ($c->user) {
                    $full = trim(($c->user->prenom ?? '') . ' ' . ($c->user->nom ?? ''));
                    if ($full !== '') $clientName = $full;
                    elseif (!empty($c->user->email)) $clientName = $c->user->email;
                }
                return [
                    'id' => $c->id,
                    'code' => $c->code_commandes,
                    'client' => $clientName,
                    'montant' => $c->prix_total_commande,
                    'status' => $c->status,
                    'date' => $c->created_at->format('d/m/Y'),
                ];
            });

        // Passer les variables à la vue
        return view('maison', compact(
            'active_link',
            'statsSupriz',
            'nombresAbonnes',
            'nombreClics',
            'clientsActifs',
            'activityData',
            'utilisateursRecents',
            // e-commerce
            'totalVentes',
            'totalLivraison',
            'countEncours',
            'countConfirmer',
            'countLivree',
            'nbProduits',
            'nbAchatsTotal',
            'ecomActivity',
            'commandesRecentes'
        ));
    }
}
