<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre',
        'contenu',
        'sous_categorie_id',
        'media_path',
        'media_type',
        'background_path',
        'images',
        'musique_id',
        'date_expiration',
        'expiration_type',
        'slug',
        'is_active',
        'views_count',
        'max_views',
        'unique_view',
    ];

    protected $casts = [
        'date_expiration' => 'datetime',
        'is_active' => 'boolean',
        'images' => 'array',
        'unique_view' => 'boolean',
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
     * Relation avec User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
     * Relation avec les vues
     */
    public function views()
    {
        return $this->hasMany(MessageView::class);
    }

    /**
     * Relation avec les réactions
     */
    public function reactions()
    {
        return $this->hasMany(MessageReaction::class);
    }

    /**
     * Vérifier si le message est expiré (date, vue unique, ou max vues)
     */
    public function isExpired()
    {
        // Expiré par date
        if ($this->date_expiration && $this->date_expiration->isPast()) {
            return true;
        }

        // Expiré par vue unique (déjà vu 2 fois)
        if ($this->unique_view && $this->views_count >= 2) {
            return true;
        }

        // Expiré par max vues atteint
        if ($this->hasReachedMaxViews()) {
            return true;
        }

        return false;
    }

    /**
     * Vérifier si le max_views est atteint
     */
    public function hasReachedMaxViews()
    {
        if ($this->max_views === null) {
            return false;
        }
        return $this->views_count >= $this->max_views;
    }

    /**
     * Incrémenter le compteur de vues
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Vérifier si un viewer a déjà vu ce message (pour vue unique)
     */
    public function hasBeenViewedBy($ip = null, $deviceId = null)
    {
        $query = $this->views();

        if ($deviceId) {
            return $query->where('device_id', $deviceId)->exists();
        }

        if ($ip) {
            return $query->where('viewer_ip', $ip)->exists();
        }

        return false;
    }

    /**
     * Enregistrer une vue (uniquement si pas déjà vue par ce device/ip)
     */
    public function recordView($ip = null, $deviceId = null, $userId = null)
    {
        // Vérifier si cette vue existe déjà
        if ($this->hasBeenViewedBy($ip, $deviceId)) {
            return null; // Vue déjà enregistrée
        }

        return $this->views()->create([
            'viewer_ip' => $ip,
            'device_id' => $deviceId,
            'user_id' => $userId,
        ]);
    }
}
