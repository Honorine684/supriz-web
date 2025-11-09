<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BilanMessageSupriz extends Model
{
    use HasFactory;

    // Définir la table associée
    protected $table = 'bilan_message_supriz';

    // Définir les champs remplissables
    protected $fillable = [
        'nom',
        'nbr_total',
        'bilanId',
    ];

    // Relation inverse avec Bilan
    public function bilan()
    {
        return $this->belongsTo(Bilan::class, 'bilanId');
    }
}
