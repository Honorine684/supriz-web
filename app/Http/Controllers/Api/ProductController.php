<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\HommeCategory;
use App\Models\HommeSousCategorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function getProductsByCategory(Request $request)
    {
        try {
            $request->validate([
                'category_name' => 'required|string|exists:homme_categories,name_id_categories',
                'subcategory_name' => 'required|string|exists:homme_sous_categories,name_sous_categories',
            ]);

            $categoryId = HommeCategory::where('name_id_categories', $request->category_name)->value('id');
            $subcategoryId = HommeSousCategorie::where('name_sous_categories', $request->subcategory_name)
                ->where('type_categorie_id', $categoryId)
                ->value('id');

            if (!$subcategoryId) {
                return response()->json([
                    'status' => false,
                    'message' => 'No matching subcategory found'
                ], 404);
            }

            $products = Product::whereHas('sousCategories', function($query) use ($subcategoryId) {
                $query->where('homme_sous_categories.id', $subcategoryId);
            })->get();

            // Ajouter les informations de catégorie et de sous-catégorie aux produits
            $products->each(function($product) use ($request) {
                $product->category = $request->category_name;
                $product->subCategory = $request->subcategory_name;
            });

            return response()->json([
                'status' => true,
                'message' => 'Products retrieved successfully',
                'data' => $products
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Error in getProductsByCategory: ' . $th->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while processing your request.'
            ], 422);
        }
    }

    public function index()
    {
        try {
            $products = Product::all();
            return response()->json($products, 200);
        } catch (\Throwable $th) {
            Log::error('Error in index: ' . $th->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while processing your request.'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::find($id);
            if ($product) {
                return response()->json($product, 200);
            } else {
                return response()->json(['error' => 'Product not found'], 404);
            }
        } catch (\Throwable $th) {
            Log::error('Error in show: ' . $th->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while processing your request.'
            ], 500);
        }
    }
}
