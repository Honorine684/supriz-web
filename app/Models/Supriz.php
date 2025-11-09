<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supriz extends Model
{
    use HasFactory;

    protected $table = 'supriz'; // Nom de la table

    protected $fillable = [
        'nom_categorie_suprise',
        'users_id',
        'numero_destinateur',
        'prix',
        'Description_supriz',
        'status'
    ];

    // Relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
