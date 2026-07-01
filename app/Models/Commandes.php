<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commandes extends Model
{
    use HasFactory;

    protected $table = 'commandes'; // Nom de la table
    protected $primaryKey = 'id'; // Clé primaire

    protected $fillable = [
        'users_id',
        'prix_total_commande',
        'prix_total_livraison',
        'numero_beneficier',
        'selectedOption',
        'status',
        'code_commandes',
        'date_livraison',
        'heure_livraison',
        'latitude_livraison',
        'longitude_livraison',
        'adresse_livraison',
    ];

    protected $casts = [
        'date_livraison' => 'date',
        'latitude_livraison' => 'decimal:7',
        'longitude_livraison' => 'decimal:7',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id'); // Assurez-vous que 'user_id' est le bon nom de la clé étrangère
    }

    /**
     * Relation avec les achats.
     */
    public function achats()
    {
        return $this->hasMany(Achat::class, 'commande_id');
    }
}