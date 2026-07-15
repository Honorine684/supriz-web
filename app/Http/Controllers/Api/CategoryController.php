<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HommeCategory;
use App\Models\HommeSousCategorie;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $categories = HommeCategory::where('name_id_categories', '!=', 's')->get();
        return response()->json($categories);
    }

    public function getSubcategories($categoryId)
    {
        $subcategories = HommeSousCategorie::where('type_categorie_id', $categoryId)
            ->where('is_active', true)
            ->get();
        return response()->json($subcategories);
    }
}
