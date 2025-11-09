<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achat extends Model
{
    use HasFactory;

    protected $table = 'achat'; // Nom de la table
    protected $primaryKey = 'id'; // Clé primaire

    protected $fillable = [
        'product_id', // ID du produit
        'commande_id', // ID de la commande
        'qte_achat', // Quantité d'achat
        'description_de_personnalisation', // Description de personnalisation
    ];

    /**
     * Relation avec la commande.
     */
    public function commande()
    {
        return $this->belongsTo(Commandes::class, 'commande_id');
    }

    /**
     * Relation avec le produit.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
