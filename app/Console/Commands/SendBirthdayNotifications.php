<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FriendBirthday;
use App\Models\User;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendBirthdayNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthdays:send-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer les notifications de rappel d\'anniversaire aux utilisateurs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $firebaseService = new FirebaseService();
        $now = Carbon::now();
        $today = $now->format('m-d');
        $currentTime = $now->format('H:i');

        $this->info("Vérification des anniversaires pour {$today} à {$currentTime}");

        // Récupérer tous les anniversaires actifs du jour qui n'ont pas encore reçu de notification cette année
        $birthdays = FriendBirthday::where('is_active', true)
            ->where('notification_sent_this_year', false)
            ->whereRaw("DATE_FORMAT(birthday_date, '%m-%d') = ?", [$today])
            ->with('user')
            ->get();

        $this->info("Trouvé {$birthdays->count()} anniversaire(s) aujourd'hui");

        $notificationsSent = 0;

        foreach ($birthdays as $birthday) {
            // Vérifier si c'est l'heure d'envoyer la notification
            // On calcule l'heure de notification en soustrayant les minutes de rappel
            $reminderTime = $birthday->reminder_time ?? '09:00';
            $reminderMinutesBefore = $birthday->reminder_minutes_before ?? 0;

            // Parser l'heure de rappel
            $notificationTime = Carbon::createFromFormat('H:i', $reminderTime);
            $notificationTime->subMinutes($reminderMinutesBefore);

            $scheduledTime = $notificationTime->format('H:i');

            // Vérifier si c'est le bon moment (avec une marge de 5 minutes)
            $scheduledCarbon = Carbon::createFromFormat('H:i', $scheduledTime);
            $diffMinutes = abs($now->diffInMinutes($scheduledCarbon, false));

            if ($diffMinutes > 5) {
                // Pas encore l'heure
                $this->info("  - {$birthday->friend_name}: notification prévue à {$scheduledTime} (dans " . ($scheduledCarbon->diffInMinutes($now, false) * -1) . " min)");
                continue;
            }

            // Vérifier que l'utilisateur existe et a un token FCM
            $user = $birthday->user;
            if (!$user) {
                $this->warn("  - {$birthday->friend_name}: utilisateur introuvable");
                continue;
            }

            if (!$user->fcm_token) {
                $this->warn("  - {$birthday->friend_name}: pas de token FCM pour l'utilisateur {$user->nom}");
                continue;
            }

            // Envoyer la notification
            $title = "🎂 Anniversaire de {$birthday->friend_name} !";
            $body = "C'est aujourd'hui ! Prépare un message Supriz pour {$birthday->friend_name} 🎁";

            $result = $firebaseService->sendNotification(
                $user->fcm_token,
                $title,
                $body,
                [
                    'type' => 'birthday_reminder',
                    'birthday_id' => $birthday->id,
                    'friend_name' => $birthday->friend_name,
                    'friend_phone' => $birthday->friend_phone ?? '',
                ]
            );

            if ($result) {
                // Marquer comme envoyé pour cette année
                $birthday->notification_sent_this_year = true;
                $birthday->save();

                $notificationsSent++;
                $this->info("  ✓ Notification envoyée pour {$birthday->friend_name} à {$user->nom}");

                Log::info("Birthday notification sent", [
                    'birthday_id' => $birthday->id,
                    'friend_name' => $birthday->friend_name,
                    'user_id' => $user->id,
                    'user_name' => $user->nom,
                ]);
            } else {
                $this->error("  ✗ Échec de l'envoi pour {$birthday->friend_name}");

                Log::error("Birthday notification failed", [
                    'birthday_id' => $birthday->id,
                    'friend_name' => $birthday->friend_name,
                    'user_id' => $user->id,
                ]);
            }
        }

        $this->info("Terminé: {$notificationsSent} notification(s) envoyée(s)");

        // Réinitialiser le flag pour l'année prochaine (à exécuter le 1er janvier)
        if ($now->month === 1 && $now->day === 1 && $now->hour === 0 && $now->minute < 5) {
            FriendBirthday::query()->update(['notification_sent_this_year' => false]);
            $this->info("Flags de notification réinitialisés pour la nouvelle année");
        }

        return Command::SUCCESS;
    }
}
