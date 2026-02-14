<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArrierePlan;
use App\Models\SousCategorie;
use App\Models\Categorie;
use Illuminate\Support\Facades\Storage;

class ArrierePlanController extends Controller
{
    /**
     * Afficher la page de gestion des arrière-plans
     */
    public function index()
    {
        // Récupérer les sous-catégories Supriz pour le formulaire
        $suprizCategorie = Categorie::where('name_id_categories', 's')->first();
        $sousCategories = SousCategorie::where('type_categorie_id', $suprizCategorie->id ?? null)->get();

        // Récupérer les arrière-plans groupés par catégorie
        $arrierePlansGroupes = [];
        foreach ($sousCategories as $sousCategorie) {
            $arrierePlans = ArrierePlan::where('sous_categorie_id', $sousCategorie->id)
                ->orderBy('ordre')
                ->orderBy('created_at', 'desc')
                ->get();
            if ($arrierePlans->count() > 0) {
                $arrierePlansGroupes[$sousCategorie->name_sous_categories] = $arrierePlans;
            }
        }

        $active_link = "arriere-plan";
        return view('arriere-plan', compact('arrierePlansGroupes', 'sousCategories', 'active_link'));
    }

    /**
     * Ajouter un nouvel arrière-plan
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'sous_categorie_id' => 'required|exists:homme_sous_categories,id',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048', // Max 2MB
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('arriere-plans', 'public');
        }

        // Définir l'ordre (dernier + 1)
        $maxOrdre = ArrierePlan::where('sous_categorie_id', $request->sous_categorie_id)->max('ordre') ?? 0;

        ArrierePlan::create([
            'nom' => $request->nom,
            'image' => $imagePath,
            'sous_categorie_id' => $request->sous_categorie_id,
            'is_active' => true,
            'ordre' => $maxOrdre + 1,
        ]);

        return redirect()->route('admin.arriere-plan')->with('success', 'Arrière-plan ajouté avec succès');
    }

    /**
     * Mettre à jour un arrière-plan
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'sous_categorie_id' => 'required|exists:homme_sous_categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $arrierePlan = ArrierePlan::find($id);

        if (!$arrierePlan) {
            return redirect()->back()->with('error', 'Arrière-plan non trouvé');
        }

        $imagePath = $arrierePlan->image;

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($arrierePlan->image && Storage::exists('public/' . $arrierePlan->image)) {
                Storage::delete('public/' . $arrierePlan->image);
            }

            $imagePath = $request->file('image')->store('arriere-plans', 'public');
        }

        $arrierePlan->update([
            'nom' => $request->nom,
            'image' => $imagePath,
            'sous_categorie_id' => $request->sous_categorie_id,
        ]);

        return redirect()->route('admin.arriere-plan')->with('success', 'Arrière-plan mis à jour avec succès');
    }

    /**
     * Activer/Désactiver un arrière-plan
     */
    public function toggleStatus($id)
    {
        $arrierePlan = ArrierePlan::find($id);

        if (!$arrierePlan) {
            return redirect()->back()->with('error', 'Arrière-plan non trouvé');
        }

        $arrierePlan->is_active = !$arrierePlan->is_active;
        $arrierePlan->save();

        $status = $arrierePlan->is_active ? 'activé' : 'désactivé';
        return redirect()->route('admin.arriere-plan')->with('success', "Arrière-plan {$status} avec succès");
    }

    /**
     * Supprimer un arrière-plan
     */
    public function destroy($id)
    {
        $arrierePlan = ArrierePlan::find($id);

        if (!$arrierePlan) {
            return redirect()->back()->with('error', 'Arrière-plan non trouvé');
        }

        // Supprimer l'image
        if ($arrierePlan->image && Storage::exists('public/' . $arrierePlan->image)) {
            Storage::delete('public/' . $arrierePlan->image);
        }

        $arrierePlan->delete();

        return redirect()->route('admin.arriere-plan')->with('success', 'Arrière-plan supprimé avec succès');
    }

    /**
     * Récupérer les arrière-plans d'une catégorie (AJAX)
     */
    public function getArrierePlansByCategorie($categorieId)
    {
        $arrierePlans = ArrierePlan::where('sous_categorie_id', $categorieId)
            ->where('is_active', true)
            ->orderBy('ordre')
            ->get();

        return response()->json([
            'success' => true,
            'arriere_plans' => $arrierePlans->map(function ($ap) {
                return [
                    'id' => $ap->id,
                    'nom' => $ap->nom,
                    'image_url' => $ap->image_url,
                    'ordre' => $ap->ordre,
                ];
            }),
            'has_arriere_plans' => $arrierePlans->count() > 0
        ]);
    }
}
