<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depenses extends Model
{
    use HasFactory;
    protected $table = 'depenses'; // Assure-toi que le nom de la table est correct

    protected $primaryKey = 'id'; // Nom de la clé primaire, par défaut c'est 'id'

    // Les attributs qui sont mass assignable
    protected $fillable = [
        'depenses',  
        'prix',
    ];

}
