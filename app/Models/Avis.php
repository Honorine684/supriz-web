<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'message',
        'note',
        'visible',
        'initiales',
    ];

    protected $casts = [
        'visible' => 'boolean',
        'note' => 'integer',
    ];

    /**
     * Scope pour récupérer uniquement les avis visibles
     */
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    /**
     * Génère les initiales à partir du nom
     */
    public static function genererInitiales(string $nom): string
    {
        $mots = explode(' ', trim($nom));
        if (count($mots) >= 2) {
            return strtoupper(mb_substr($mots[0], 0, 1) . mb_substr($mots[1], 0, 1));
        }
        return strtoupper(mb_substr($nom, 0, 2));
    }
}
