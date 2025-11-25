<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Musique extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'fichier_audio',
        'api_url',
        'sous_categorie_id',
        'duree',
        'is_active',
        'is_predefined',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_predefined' => 'boolean',
    ];

    /**
     * Relation avec SousCategorie
     */
    public function sousCategorie()
    {
        return $this->belongsTo(SousCategorie::class, 'sous_categorie_id');
    }
}
