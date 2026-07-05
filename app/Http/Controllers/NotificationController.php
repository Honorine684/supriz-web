<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use App\Services\FirebaseService;

class NotificationController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    //
    public function notification()
    {
        $active_link = "m";
        $notifs = Notification::latest()->get();
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
            'titre' => 'required|string|max:255',
            'contenus' => 'required|string',
        ]);

        // Créer une nouvelle notification
        $notification = Notification::create([
            'titre' => $request->input('titre'),
            'contenus' => $request->input('contenus')
        ]);

        // Envoyer les notifications push Firebase à tous les utilisateurs avec un fcm_token
        $this->sendPushNotificationToAllUsers($notification);

        // Rediriger vers la page des notifications avec un message de succès
        return redirect()->route('admin.notifications')->with('success', 'Notification envoyée avec succès.');
    }

    /**
     * Envoie une notification push à tous les utilisateurs ayant un fcm_token
     */
    protected function sendPushNotificationToAllUsers(Notification $notification)
    {
        // Récupérer tous les utilisateurs avec un fcm_token valide
        $users = User::whereNotNull('fcm_token')
            ->where('fcm_token', '!=', '')
            ->get();

        $successCount = 0;
        $failCount = 0;

        foreach ($users as $user) {
            $result = $this->firebaseService->sendNotification(
                $user->fcm_token,
                $notification->titre, // Titre de la notification
                $notification->contenus, // Contenu du message
                [
                    'type' => 'admin_notification',
                    'notification_id' => (string) $notification->id,
                ]
            );

            if ($result) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        \Log::info("Notifications push envoyées: {$successCount} succès, {$failCount} échecs sur " . $users->count() . " utilisateurs");
    }

}