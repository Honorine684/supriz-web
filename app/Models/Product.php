<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'products'; // Nom de la table
    protected $primaryKey = 'id'; // Clé primaire

    protected $fillable = [
        'title', // Nom du produit
        'price',
        'description', // Nom du produit
        'entreprise',
        'marchand_id',
        'evaluation',
        'prix_promo',
        'prix_livraison',
        'info',
        'photo_main',
        'photo_secondary',
        'personnalisable'
    ];

    public function sousCategories()
    {
        return $this->belongsToMany(HommeSousCategorie::class, 'product_sous_categorie', 'product_id', 'sous_categorie_id');
    }

    public function achats()
    {
        return $this->hasMany(Achat::class, 'product_id');
    }

    public function marchand()
    {
        return $this->belongsTo(Marchand::class, 'marchand_id');
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'badge_product', 'product_id', 'badge_id');
    }
}
