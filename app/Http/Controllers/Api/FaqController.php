<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    /**
     * Retrieve FAQs
     * @return \Illuminate\Http\JsonResponse
     */
    public function faqapp(){
        // Récupérer toutes les FAQ
        $faqs = Faq::all();

        // Vérifier si la collection est vide
        if ($faqs->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Aucune FAQ trouvée.',
            ], 404);
        } else {
            return response()->json([
                'status' => true,
                'faq' => $faqs,
            ], 200);
        }
    }
}
