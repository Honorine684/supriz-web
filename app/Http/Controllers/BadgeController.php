<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;

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
            'name' => 'required|string|max:50|unique:badges,name',
            'icon' => 'nullable|string|max:40',
        ]);

        Badge::create([
            'name' => trim($request->input('name')),
            'color' => '#005792',
            'icon' => $request->input('icon'),
        ]);

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
            'name' => 'required|string|max:50|unique:badges,name,' . $id,
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
