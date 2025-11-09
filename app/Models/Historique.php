<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    use HasFactory;
    protected $table = 'historiques'; // Nom de la table

    protected $fillable = [
        'id_commande',
        'users_id',
        'description',
        'type',
        'prix',
        'code_historiques',
    ];
}
