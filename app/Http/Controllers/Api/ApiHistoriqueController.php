<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // Import correct du contrôleur parent
use App\Models\Historique; // Vérifier le modèle
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiHistoriqueController extends Controller
{
    public function index()
    {
        // Récupérer l'ID de l'utilisateur connecté
        $userId = Auth::id();

        // Vérifier si l'utilisateur est bien connecté
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Récupérer les historiques de l'utilisateur
        $historiques = Historique::where('users_id', $userId)->get();

        // Mapper les données pour correspondre à la structure souhaitée
        $formattedHistoriques = $historiques->map(function ($historique) {
            return [
                'description' => $historique->description,
                'prix' => $historique->prix,
                'code_historiques' => $historique->code_historiques,
                'created_at' => $historique->created_at->toISOString(), // Formater la date
            ];
        });

        // Retourner les données au format JSON
        return response()->json($formattedHistoriques);
    }
}
