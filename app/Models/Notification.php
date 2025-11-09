<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    
    use HasFactory;
    protected $table = 'notifications'; // Assure-toi que le nom de la table est correct

    protected $primaryKey = 'id'; // Nom de la clé primaire, par défaut c'est 'id'

    protected $fillable = [
        'contenus'
    ];

    public $timestamps = true; // Cette ligne est optionnelle; par défaut, Laravel gère les timestamps

        // Define a one-to-many relationship with NotificationDeleteUser
        public function notificationDeleteUsers()
        {
            return $this->hasMany(NotificationDeleteUser::class, 'id_notification');
        }

        // Define a one-to-many relationship with notificationMakereadUsers
    public function notificationMakereadUsers()
    {
        return $this->hasMany(NotificationDeleteUser::class, 'id_notification');
    }

}
