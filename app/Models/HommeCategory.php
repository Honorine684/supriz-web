<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HommeCategory extends Model
{
    use HasFactory;

    protected $table = 'homme_categories'; // Nom de la table
    protected $primaryKey = 'id'; // Clé primaire

    protected $fillable = [
        'libelle_homme_categories', // Attribut correspondant à la colonne
    ];

    /**
     * Relation avec les sous-catégories.
     */
    public function sousCategories()
    {
        return $this->hasMany(HommeSousCategorie::class, 'type_categorie_id');
    }
}