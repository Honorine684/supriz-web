<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SousCategorie extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'homme_sous_categories'; // Nom de la table
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'type_categorie_id',   // Foreign key to 'homme_categories'
        'name_sous_categories',
        'placeholder_texte',
        'prix',
        'image',
        'icone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relation with the 'homme_categories' table.
     */
   /**
     * Définir la relation avec le modèle Categorie.
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'type_categorie_id');
    }

    /**
     * Relation avec les musiques
     */
    public function musiques()
    {
        return $this->hasMany(Musique::class, 'sous_categorie_id');
    }
}
