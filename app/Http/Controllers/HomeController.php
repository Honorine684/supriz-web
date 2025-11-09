<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Depenses;
use App\Models\Commandes;
use App\Models\Bilan;
use App\Models\Supriz;
use App\Models\BilanMessageSupriz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importer la classe DB
use Carbon\Carbon;

class HomeController extends Controller
{
    public function home()
    {
        // Définir la variable active_link
        $active_link = "h";

        // ============ STATISTIQUES SUPRIZ PAR CATÉGORIE ============
        $categories = ['Mariage', 'Vœux', 'Anniversaire', 'Nouvel an', 'Noël'];
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
                    'nom' => $user->name ?? 'xxxxxxx',
                    'avatar' => $user->profile_photo_path ?? null,
                    'categorie' => $user->supriz->last()->nom_categorie_suprise ?? 'Catégorie',
                    'date' => $user->created_at->format('d/m/Y')
                ];
            });

        // ============ ANCIENNES STATS (POUR COMPATIBILITÉ) ============
        $user = User::all();
        $userToday = User::whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

        $activeClients = User::where('last_login_at', '>=', Carbon::now()->subDays(30))
            ->orWhere('last_purchase_at', '>=', Carbon::now()->subDays(30))
            ->get();

        $lostClients = User::where('last_login_at', '<', Carbon::now()->subMonths(6))
            ->orWhere('last_purchase_at', '<', Carbon::now()->subMonths(6))
            ->get();

        $commandes_produits = Commandes::all()->sum('prix_total_commande');
        $commandes_commission = Commandes::all()->sum('commission');
        $commandes_livraison = Commandes::all()->sum('prix_total_livraison');
        $depenses = Depenses::all()->sum('prix');
        $ventes = $commandes_produits - $commandes_commission;
        $benefices = $commandes_commission + $commandes_livraison;
        $actif = $commandes_produits;

        $monthlyTotals = Bilan::selectRaw('MONTH(created_at) as month, SUM(somme_total) as total_sum')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $data = [
            'months' => [],
            'totals' => []
        ];

        for ($i = 1; $i <= 12; $i++) {
            $data['months'][] = date('M', mktime(0, 0, 0, $i, 1));
            $data['totals'][] = $monthlyTotals->get($i)->total_sum ?? 0;
        }

        // Passer les variables à la vue
        return view('welcome', compact(
            'active_link',
            'statsSupriz',
            'nombresAbonnes',
            'nombreClics',
            'clientsActifs',
            'activityData',
            'utilisateursRecents',
            'userToday',
            'user',
            'activeClients',
            'lostClients',
            'depenses',
            'ventes',
            'actif',
            'benefices',
            'data'
        ));
    }
}
