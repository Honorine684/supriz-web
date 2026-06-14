<?php

namespace App\Http\Controllers;

use App\Models\Marchand;
use Illuminate\Http\Request;

class MarchandController extends Controller
{
    public function index()
    {
        $active_link = 'marchand';
        $marchands = Marchand::orderBy('nom')->get();
        return view('marchand', compact('active_link', 'marchands'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255|unique:marchands,nom',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'telephone' => 'nullable|string|max:30',
        ]);

        Marchand::create($data);

        return redirect()
            ->route('admin.marchand')
            ->with('success', 'Marchand créé avec succès.');
    }

    public function edit($id)
    {
        $active_link = 'marchand';
        $marchand = Marchand::findOrFail($id);
        $marchands = Marchand::orderBy('nom')->get();
        return view('marchand', compact('active_link', 'marchand', 'marchands'));
    }

    public function update(Request $request, $id)
    {
        $marchand = Marchand::findOrFail($id);

        $data = $request->validate([
            'nom' => 'required|string|max:255|unique:marchands,nom,' . $marchand->id,
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'telephone' => 'nullable|string|max:30',
        ]);

        $marchand->update($data);

        return redirect()
            ->route('admin.marchand')
            ->with('success', 'Marchand mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $marchand = Marchand::findOrFail($id);

        if ($marchand->products()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Impossible de supprimer : ce marchand a des produits associés.');
        }

        $marchand->delete();

        return redirect()
            ->route('admin.marchand')
            ->with('success', 'Marchand supprimé avec succès.');
    }
}
