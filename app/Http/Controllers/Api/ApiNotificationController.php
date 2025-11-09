<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationDeleteUser;
use App\Models\NotificationMakereadUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiNotificationController extends Controller
{
    /**
     * Return all notifications
     */
    public function index()
    {
        // Get the currently authenticated user's ID
        $userId = Auth::user()->id;

        // Get IDs of notifications marked as deleted by the user
        $deletedNotificationIds = NotificationDeleteUser::where('userId', $userId)
            ->pluck('id_notification')
            ->toArray();

        // Get IDs of notifications marked as read by the user
        $readNotificationIds = NotificationMakereadUser::where('userId', $userId)
            ->pluck('id_notification')
            ->toArray();

        // Fetch notifications excluding deleted and marking read status
        $notifications = Notification::whereNotIn('id', $deletedNotificationIds)
            ->orderBy('created_at', 'desc') // Tri par la date de création
            ->get()
            ->map(function ($notification) use ($readNotificationIds) {
                $notification->isRead = in_array($notification->id, $readNotificationIds);
                return $notification;
            });

        // Ajout de débogage
        \Log::info('Notifications fetched: ' . json_encode($notifications));

        return response()->json($notifications);
    }

    /**
     * Mark all notifications as read for the authenticated user
     */
    public function makereadAll()
    {
        // Get the authenticated user's ID
        $userId = Auth::user()->id;

        // Get the notification IDs that the user has already marked as read
        $readNotificationIds = NotificationMakereadUser::where('userId', $userId)
            ->pluck('id_notification')
            ->toArray();

        // Retrieve notifications that the user has NOT marked as read yet
        $notificationsToMarkAsRead = Notification::whereNotIn('id', $readNotificationIds)->get();

        // Mark notifications as read
        foreach ($notificationsToMarkAsRead as $notification) {
            NotificationMakereadUser::create([
                'id_notification' => $notification->id,
                'userId' => $userId,
            ]);
        }

        return response()->json(['message' => 'Notifications marked as read'], 200);
    }

    /**
     * Mark a specific notification as read for the authenticated user
     */
    public function markAsRead($id)
    {
        $userId = Auth::id();  // Récupérer l'utilisateur authentifié

        // Vérifier si la notification existe et n'a pas déjà été marquée comme lue
        $notification = Notification::findOrFail($id);

        // Vérifier si la notification est déjà marquée comme lue par cet utilisateur
        $isAlreadyRead = NotificationMakereadUser::where('id_notification', $id)
            ->where('userId', $userId)
            ->exists();

        if (!$isAlreadyRead) {
            // Marquer la notification comme lue
            NotificationMakereadUser::create([
                'id_notification' => $id,
                'userId' => $userId,
            ]);
        }

        return response()->json(['message' => 'Notification marked as read'], 200);
    }

    /**
     * Mark a specific notification as deleted for the authenticated user
     */
    public function deleteAtId(Request $request, $notificationId)
    {
        // Get the authenticated user's ID
        $userId = Auth::user()->id;

        // Check if the notification is already marked as deleted by this user
        $existingRecord = NotificationDeleteUser::where('id_notification', $notificationId)
            ->where('userId', $userId)
            ->first();

        if ($existingRecord) {
            // If the notification is already marked as deleted, return a response
            return response()->json(['message' => 'Notification already marked as deleted'], 200);
        }

        // Otherwise, add the notification to the delete table
        NotificationDeleteUser::create([
            'id_notification' => $notificationId,
            'userId' => $userId,
        ]);

        return response()->json(['message' => 'Notification successfully marked as deleted'], 200);
    }

    /**
     * Mark all notifications as deleted for the authenticated user
     */
    public function deleteAll()
    {
        // Get the authenticated user's ID
        $userId = Auth::user()->id;

        // Get the notification IDs that the user has already marked as deleted
        $deletedNotificationIds = NotificationDeleteUser::where('userId', $userId)
            ->pluck('id_notification')
            ->toArray();

        // Retrieve notifications that the user has NOT marked as deleted yet
        $notificationsToDelete = Notification::whereNotIn('id', $deletedNotificationIds)->get();

        // Mark notifications as deleted
        foreach ($notificationsToDelete as $notification) {
            NotificationDeleteUser::create([
                'id_notification' => $notification->id,
                'userId' => $userId,
            ]);
        }

        return response()->json(['message' => 'Notifications marked as deleted'], 200);
    }
}
