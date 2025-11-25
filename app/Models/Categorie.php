<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Categorie extends Model
{
    use HasFactory;
    use SoftDeletes;
    // Table associated with the model
    protected $table = 'homme_categories';

    // Primary key of the table
    protected $primaryKey = 'id';

    // Fillable fields for mass assignment
    protected $fillable = [
        'name_id_categories', // 'h', 'f', 'e', 's'
        'libelle_homme_categories', // Descriptive label for the category
    ];
    
    /**
     * Get the sub-categories related to this category.
     */
    public function sousCategories()
    {
        return $this->hasMany(SousCategorie::class, 'type_categorie_id');
    }
}
