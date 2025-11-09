<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bilan extends Model
{
    use HasFactory;

    // Définir la table associée
    protected $table = 'bilan';

    // Définir les champs remplissables
    protected $fillable = [
        'nbr_produit_vendu',
        'somme_total',
        'benefices',
        'nbr_livraison',
        'montant_total_supriz',
        'prix_total_livraison',
    ];

    // Relation avec BilanMessageSupriz
    public function messagesSupriz()
    {
        return $this->hasMany(BilanMessageSupriz::class, 'bilanId');
    }

    // Relation avec BilanEntreprise
    public function entreprises()
    {
        return $this->hasMany(BilanEntreprise::class, 'bilanId');
    }

    public static function getMonthlyTotals()
    {
        $year = date('Y'); // Année en cours

        return DB::table('bilan')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(somme_total) as total_sum'))
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();
    }
}
