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
        'users_id', // ID de l'utilisateur
        'prix_total_commande',
        'prix_total_livraison', // Prix total de la commande
        'numero_beneficier', // Numéro du bénéficiaire
        'status', // Statut de la commande (ex. "encours")
        'code_commandes', // Code unique de la commande
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