<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marchand extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'marchands';

    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'latitude',
        'longitude',
        'telephone',
        'working_days',
        'working_hours',
    ];

    // Le téléphone marchand n'est jamais exposé dans les réponses API client.
    // Pour le récupérer côté admin Supriz : ->makeVisible('telephone').
    protected $hidden = [
        'telephone',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'working_days' => 'array',
        'working_hours' => 'array',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'marchand_id');
    }
}
