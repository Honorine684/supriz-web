<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BilanEntreprise extends Model
{
    use HasFactory;

    // Définir la table associée
    protected $table = 'bilan_entreprise';

    // Définir les champs remplissables
    protected $fillable = [
        'nom',
        'nbr_produits_vendu',
        'montant_encaisse',
        'bilanId',
    ];

    // Relation inverse avec Bilan
    public function bilan()
    {
        return $this->belongsTo(Bilan::class, 'bilanId');
    }
}
