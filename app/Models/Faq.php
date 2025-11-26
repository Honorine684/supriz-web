<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faq'; // Assure-toi que le nom de la table est correct

    protected $primaryKey = 'id'; // Nom de la clé primaire, par défaut c'est 'id'

    // Les attributs qui sont mass assignable
    protected $fillable = [
        'type_faq',      // Catégorie de la question (supriz, app)
        'asq',           // Question
        'response',      // Réponse
    ];

    // Si tu utilises des timestamps dans ta table, active cette ligne
    public $timestamps = true; // Cette ligne est optionnelle; par défaut, Laravel gère les timestamps
}
