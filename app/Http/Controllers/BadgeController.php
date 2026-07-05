<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BadgeController extends Controller
{
    public function index()
    {
        $active_link = 'badges';
        $badges = Badge::orderBy('name')->get();
        return view('badges', compact('active_link', 'badges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Unicité seulement parmi les badges NON supprimés (ignore soft-delete).
            'name' => ['required', 'string', 'max:50', Rule::unique('badges', 'name')->whereNull('deleted_at')],
            'icon' => 'nullable|string|max:40',
        ]);

        $name = trim($request->input('name'));

        // Si un badge du même nom a été supprimé (soft-delete), on le restaure
        // au lieu de bloquer / créer un doublon.
        $trashed = Badge::onlyTrashed()->where('name', $name)->first();
        if ($trashed) {
            $trashed->restore();
            $trashed->update(['color' => '#005792', 'icon' => $request->input('icon')]);
        } else {
            Badge::create([
                'name' => $name,
                'color' => '#005792',
                'icon' => $request->input('icon'),
            ]);
        }

        return redirect()->back()->with('success', 'Badge créé avec succès.');
    }

    public function edit($id)
    {
        $active_link = 'badges';
        $badge = Badge::findOrFail($id);
        $badges = Badge::orderBy('name')->get();
        return view('badges', compact('active_link', 'badge', 'badges'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('badges', 'name')->ignore($id)->whereNull('deleted_at')],
            'icon' => 'nullable|string|max:40',
        ]);

        $badge = Badge::findOrFail($id);
        $badge->update([
            'name' => trim($request->input('name')),
            'color' => '#005792',
            'icon' => $request->input('icon'),
        ]);

        return redirect()->route('admin.badges')->with('success', 'Badge modifié avec succès.');
    }

    public function destroy($id)
    {
        $badge = Badge::find($id);
        if (!$badge) {
            return redirect()->back()->with('error', 'Badge introuvable.');
        }
        $badge->delete();
        return redirect()->back()->with('success', 'Badge supprimé avec succès.');
    }
}
