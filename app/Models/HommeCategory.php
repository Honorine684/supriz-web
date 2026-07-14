<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HommeCategory extends Model
{
    use HasFactory;
    // Même table que Categorie (SoftDeletes) : exclure les catégories
    // supprimées (deleted_at) de l'API consommée par l'app.
    use SoftDeletes;

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