<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use Illuminate\Http\Request;

class AvisController extends Controller
{
    /**
     * Soumettre un nouvel avis (depuis le popup)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'message' => 'nullable|string|max:500',
            'note' => 'required|integer|min:1|max:5',
        ]);

        $avis = Avis::create([
            'nom' => $validated['nom'],
            'message' => $validated['message'] ?? null,
            'note' => $validated['note'],
            'initiales' => Avis::genererInitiales($validated['nom']),
            'visible' => false, // L'admin doit valider
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Merci pour votre avis !'
        ]);
    }

    /**
     * Liste des avis pour l'admin
     */
    public function index()
    {
        $avis = Avis::orderBy('created_at', 'desc')->paginate(20);
        $active_link = 'avis';
        return view('admin.avis.index', compact('avis', 'active_link'));
    }

    /**
     * Toggle la visibilité d'un avis
     */
    public function toggleVisibility(Avis $avis)
    {
        $avis->update(['visible' => !$avis->visible]);

        return response()->json([
            'success' => true,
            'visible' => $avis->visible,
            'message' => $avis->visible ? 'Avis visible' : 'Avis masqué'
        ]);
    }

    /**
     * Supprimer un avis
     */
    public function destroy(Avis $avis)
    {
        $avis->delete();

        return response()->json([
            'success' => true,
            'message' => 'Avis supprimé'
        ]);
    }

    /**
     * Récupérer les avis visibles pour l'API (landing page)
     */
    public function getVisibleAvis()
    {
        $avis = Avis::visible()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($avis);
    }
}
