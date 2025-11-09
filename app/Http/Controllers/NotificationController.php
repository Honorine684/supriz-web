<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
class NotificationController extends Controller
{
    //
    public function notification()
    {
        $active_link = "m";
        $notifs = Notification::All();
        $user = auth()->user();
        return view('message',compact("active_link","notifs","user"));
    }


    /**
     * Enregistre une nouvelle notification.
     */
    public function store(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'contenus' => 'required|string',
        ]);

        // Créer une nouvelle notification
        Notification::create([
            'contenus' => $request->input('contenus')
            // 'user_id' => auth()->id(), // Associer à l'utilisateur connecté
        ]);

        // Rediriger vers la page des notifications avec un message de succès
        return redirect()->route('notification')->with('success', 'Notification envoyée avec succès.');
    }

}