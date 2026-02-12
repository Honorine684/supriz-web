<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicite extends Model
{
    use HasFactory;

    protected $table = 'publicites'; // Nom de la table
    protected $primaryKey = 'id'; // Clé primaire

    protected $fillable = [
        'pathImage',
        'lien',
        'sous_categorie',
    ];

    /**
     * Relation avec la sous-catégorie.
     */
    public function sousCategorie()
    {
        return $this->belongsTo(HommeSousCategorie::class, 'sous_categorie');
    }
}
