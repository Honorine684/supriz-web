<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Console\Command;
use App\Models\Bilan;
use App\Models\BilanEntreprise;
use App\Models\BilanMessageSupriz;
use App\Models\Commandes;
use App\Models\Supriz;
use App\Models\Achat;
use Carbon\Carbon;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();


Schedule::call(function () {
    try {
    $aujourdhui = Carbon::today();

        // Filtrer les commandes livrées pour aujourd'hui
        $commandes = Commandes::where('status', 'livrée')
                              ->whereDate('created_at', $aujourdhui)
                              ->get();
        $totalVentes =0;
        foreach ($commandes as $commande) {
            # code...
            // Compter le nombre de ventes
            $totalVentes += Achat::where('commande_id',$commande->id)->sum('qte_achat');
        }
       
        
        $sommeTotal = $commandes->sum('prix_total_commande');
        $benefices = $commandes->sum('commission') + $commandes->sum('prix_total_livraison');
        $prixLivraison = $commandes->sum('prix_total_livraison');

        // Filtrer les surprises livrées pour aujourd'hui
        $supriz = Supriz::where('status', 'livrée')
                        ->whereDate('created_at', $aujourdhui)
                        ->get();
        $montant_total_supriz = $supriz->sum('prix');

        // Créer un nouveau bilan
        $bilan = Bilan::create([
            'nbr_produit_vendu' => $totalVentes,
            'somme_total' => $sommeTotal,
            'nbr_livraison'=>$totalVentes,
            'benefices' => $benefices,
            'montant_total_supriz' => $montant_total_supriz,
            'prix_total_livraison' => $prixLivraison,
        ]);

        // Ajouter le BilanMessageSupriz
        $categoriesSupriz = $supriz->groupBy('nom_categorie_suprise');
        foreach ($categoriesSupriz as $nomCategorie => $categorySupriz) {
            BilanMessageSupriz::create([
                'nom' => $nomCategorie,
                'nbr_total' => $categorySupriz->count(),
                'bilanId' => $bilan->id,
            ]);
        }

        // Commandes avec produits
        $commandesAvecAchatsProduits = DB::table('commandes')
            ->join('achat', 'commandes.id', '=', 'achat.commande_id')
            ->join('products', 'achat.product_id', '=', 'products.id')
            ->where('commandes.status', 'livrée')
            ->whereDate('commandes.created_at', $aujourdhui)
            ->select(
                'commandes.id as commande_id',
                'commandes.prix_total_commande',
                'commandes.prix_total_livraison',
                'commandes.commission',
                'commandes.numero_beneficier',
                'products.id as product_id',
                'products.entreprise as product_entreprise',
                DB::raw('SUM(achat.qte_achat) as nbr_produits_vendu'),
                DB::raw('SUM(commandes.prix_total_commande  - commandes.commission) as montant_encaisse')
            )
            ->groupBy('products.entreprise', 'products.id', 'commandes.id')
            ->get();

        // Ajouter les bilans d'entreprises
        $entreprises = $commandesAvecAchatsProduits->groupBy('product_entreprise');
        foreach ($entreprises as $entreprise) {
            BilanEntreprise::create([
                'nom' => $entreprise->first()->product_entreprise,
                'nbr_produits_vendu' => $entreprise->sum('nbr_produits_vendu'),
                'montant_encaisse' => $entreprise->sum('montant_encaisse'),
                'bilanId' => $bilan->id,
            ]);
        }

    } catch (\Exception $e) {
        Log::error('Erreur dans la tâche planifiée: ' . $e->getMessage());
    }
// })->timezone('Africa/Porto-Novo')->at('01:48');
})->timezone('Africa/Porto-Novo')->daily();