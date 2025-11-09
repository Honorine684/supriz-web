<?php

namespace App\Http\Controllers\Api;

use App\Models\Publicite;
use App\Http\Controllers\Controller;

class PubliciteController extends Controller
{
    public function show()
    {
        // Récupérer les publicités avec les chemins d'images, la catégorie et la sous-catégorie
        $publicites = Publicite::with(['sousCategorie.categorie'])->get();

        // Retourner les données sous forme de JSON
        return response()->json($publicites);
    }
}
