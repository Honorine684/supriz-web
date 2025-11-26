<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Supriz;
use App\Models\SousCategorie;
use App\Models\Categorie;
use Illuminate\Http\Request;
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
            // Messages du mois en cours
            $currentMonth = Supriz::where('nom_categorie_suprise', $categorie)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();

            // Messages du mois précédent
            $previousMonth = Supriz::where('nom_categorie_suprise', $categorie)
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

        // Nombre de clics (nombre total de messages Supriz créés ce mois)
        $nombreClics = Supriz::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Clients actifs (utilisateurs ayant créé un message ce mois)
        $clientsActifs = User::whereHas('supriz', function($query) {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        })->count();

        // ============ ACTIVITÉ MENSUELLE DU SITE ============
        $monthlyActivity = Supriz::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
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

        // ============ UTILISATEURS RÉCENTS ============
        $utilisateursRecents = User::with('supriz')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'nom' => $user->nom ?? 'xxxxxxx',
                    'avatar' => $user->profil_image ?? null,
                    'categorie' => $user->supriz->last()->nom_categorie_suprise ?? 'Catégorie',
                    'date' => $user->created_at->format('d/m/Y')
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
