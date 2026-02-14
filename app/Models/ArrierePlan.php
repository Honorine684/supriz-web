<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArrierePlan extends Model
{
    use HasFactory;

    protected $table = 'arriere_plans';

    protected $fillable = [
        'nom',
        'image',
        'sous_categorie_id',
        'is_active',
        'ordre',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec SousCategorie
     */
    public function sousCategorie()
    {
        return $this->belongsTo(SousCategorie::class, 'sous_categorie_id');
    }

    /**
     * Obtenir l'URL complète de l'image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }
}
