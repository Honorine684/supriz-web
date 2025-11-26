<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;


class FaqController extends Controller
{
    //
    public function faq(){
        $active_link = "faq"; 
        $faqs = Faq::All();
        // Passer la variable à la vue en utilisant compact()
        return view('faq', compact('active_link','faqs'));
    }

    public function store(Request $request)
    {
        // Valider les données
        $request->validate([
            'type_faq' => 'required|in:supriz,app',
            'question_faq' => 'required|string',
            'reponse_faq' => 'required|string',
        ]);

        // Créer une nouvelle FAQ
        Faq::create([
            'type_faq' => $request->input('type_faq'),
            'asq' => $request->input('question_faq'),
            'response' => $request->input('reponse_faq'),
        ]);

        // Rediriger ou afficher un message de succès
        return redirect()->back()->with('success', 'La FAQ a été enregistrée avec succès !');
    }

    public function deleteFaq($id)
{
    // Récupère l'élément par son id
    $faq = Faq::find($id);

    // Vérifie si l'élément existe
    if ($faq) {
        $faq->delete();  // Supprime l'élément
        return redirect()->back()->with('success', 'Faq supprimé avec succès.');
    } else {
        return redirect()->back()->with('error', 'Faq introuvable.');
    }
}


public function edit($id)
{
    // Trouver la FAQ à modifier
    $faq = Faq::findOrFail($id);
    $active_link = "faq"; 
    $faqs = Faq::all();
    return view('faq', compact('faq', 'faqs','active_link'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'type_faq' => 'required|in:supriz,app',
        'question_faq' => 'required|string',
        'reponse_faq' => 'required|string',
    ]);

    // Trouver la FAQ à modifier
    $faq = Faq::findOrFail($id);

    // Mettre à jour la FAQ
    $faq->update([
        'type_faq' => $request->input('type_faq'),
        'asq' => $request->input('question_faq'),
        'response' => $request->input('reponse_faq'),
    ]);

    return redirect()->back()->with('success', 'La FAQ a été modifiée avec succès !');
}

}
