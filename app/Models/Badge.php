<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Badge extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'badges';

    protected $fillable = [
        'name',
        'color',
        'icon',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'badge_product', 'badge_id', 'product_id');
    }
}
