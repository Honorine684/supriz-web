<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'numero',
        'nbr_produit_panier',
        'profil_image',
        'password',
        'email_verified_at',
        'last_login_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Ensure password is hashed
    ];

    // Relation avec le modèle Supriz
    public function supriz()
    {
        return $this->hasMany(Supriz::class, 'users_id');
    }

    // Relation avec le modèle NotificationDeleteUser
    public function notificationDeleteUsers()
    {
        return $this->hasMany(NotificationDeleteUser::class, 'userId');
    }

        // Relation avec le modèle NotificationDeleteUser
    public function notificationMakereadUser()
    {
        return $this->hasMany(NotificationMakereadUser::class, 'userId');
    }

    // Scope pour les utilisateurs actifs
    public function scopeActive($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

        // Méthode utilitaire pour mettre à jour le profil
        public function updateProfile(array $data)
        {
            // Le cast 'hashed' sur le champ password gère automatiquement le hachage
            return $this->update($data);
        }
        
}
