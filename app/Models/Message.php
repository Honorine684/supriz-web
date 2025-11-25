<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'contenu',
        'sous_categorie_id',
        'media_path',
        'media_type',
        'musique_id',
        'date_expiration',
        'slug',
        'is_active',
        'views_count',
    ];

    protected $casts = [
        'date_expiration' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Générer un slug unique lors de la création
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($message) {
            if (empty($message->slug)) {
                $message->slug = Str::random(10);
            }
        });
    }

    /**
     * Relation avec SousCategorie
     */
    public function sousCategorie()
    {
        return $this->belongsTo(SousCategorie::class, 'sous_categorie_id');
    }

    /**
     * Relation avec Musique
     */
    public function musique()
    {
        return $this->belongsTo(Musique::class, 'musique_id');
    }

    /**
     * Vérifier si le message est expiré
     */
    public function isExpired()
    {
        return $this->date_expiration->isPast();
    }

    /**
     * Incrémenter le compteur de vues
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }
}
