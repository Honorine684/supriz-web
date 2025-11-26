<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\SousCategorie;
use App\Models\Categorie;
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

        // Passer les variables à la vue
        return view('maison', compact(
            'active_link',
            'statsSupriz',
            'nombresAbonnes',
            'nombreClics',
            'clientsActifs',
            'activityData',
            'utilisateursRecents'
        ));
    }
}
