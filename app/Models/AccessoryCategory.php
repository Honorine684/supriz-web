<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessoryCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_accessory_categories',
        'libelle_accessory_categories',
    ];

    /**
     * Get the products for the accessory category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
