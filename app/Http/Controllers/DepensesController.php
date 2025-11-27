<?php

namespace App\Http\Controllers;
use App\Models\Depenses;
use Illuminate\Http\Request;

class DepensesController extends Controller
{
    //
    public function depenses(){
        $depenses = Depenses::All();
        $active_link = "dep";
        return view('depenses', compact('active_link','depenses'));
    }

    public function store(Request $request)
    {
        // Validation des champs du formulaire
        $request->validate([
            'depenses' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
        ]);
    
        // Créer une nouvelle dépense
        Depenses::create([
            'depenses' => $request->depenses,
            'prix' => $request->prix,
        ]);
    
        return redirect()->route('admin.depenses')->with('success', 'Dépense ajoutée avec succès');
    }

    public function deleteDepenses($id)
{
    // Récupère l'élément par son id
    $depenses = Depenses::find($id);

    // Vérifie si l'élément existe
    if ($depenses) {
        $depenses->delete();  // Supprime l'élément
        return redirect()->back()->with('success', 'Depenses supprimé avec succès.');
    } else {
        return redirect()->back()->with('error', 'Depenses introuvable.');
    }
}


public function edit($id)
{
    $depenseEdit = Depenses::find($id);
    $active_link = "dep";
    $depenses = Depenses::all();
    return view('depenses', compact('depenses', 'depenseEdit','active_link'));
}

public function update(Request $request, $id)
{
    // Validation des champs
    $request->validate([
        'depenses' => 'required|string|max:255',
        'prix' => 'required|numeric|min:0',
    ]);

    // Récupération de la dépense
    $depense = Depenses::find($id);

    if ($depense) {
        $depense->update([
            'depenses' => $request->depenses,
            'prix' => $request->prix,
        ]);

        return redirect()->route('admin.depenses')->with('success', 'Dépense modifiée avec succès.');
    } else {
        return redirect()->route('admin.depenses')->with('error', 'Dépense introuvable.');
    }
}

    
}
