<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HommeSousCategorie extends Model
{
    use HasFactory;
    // Même table que SousCategorie (SoftDeletes) : on doit exclure les
    // sous-catégories supprimées (deleted_at) partout dans l'app.
    use SoftDeletes;

    protected $table = 'homme_sous_categories'; // Nom de la table
    protected $primaryKey = 'id'; // Clé primaire

    protected $fillable = [
        'name_sous_categories', // Attribut correspondant à la colonne
        'type_categorie', // Clé étrangère qui relie à la table `homme_categories`
    ];

    /**
     * Relation avec la catégorie homme.
     */
    public function categorie()
    {
        return $this->belongsTo(HommeCategory::class, 'type_categorie_id');
    }

    /**
     * Relation avec les publicités.
     */
    public function publicites()
    {
        return $this->hasMany(Publicite::class, 'sous_categorie');
    }
}
