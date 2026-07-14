<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FriendBirthday;
use App\Models\Notification;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
    /**
     * Clé secrète protégeant les endpoints cron. Définie via .env (CRON_SECRET_KEY),
     * jamais en dur dans le code versionné.
     */
    private function cronSecret(): string
    {
        return (string) config('app.cron_secret_key');
    }

    private function secretIsValid(Request $request): bool
    {
        $secret = $this->cronSecret();
        // Rejeter si aucun secret n'est configuré, ou s'il ne correspond pas.
        return $secret !== '' && hash_equals($secret, (string) $request->query('key'));
    }

    /**
     * Endpoint pour cron-job.org - Envoyer les notifications d'anniversaire
     *
     * LOGIQUE: reminder_minutes_before = minutes AVANT 00h00 du jour de l'anniversaire
     * Exemple: anniversaire le 24 février, reminder_minutes_before = 180 (3h)
     *          → notification envoyée le 23 février à 21h00
     */
    public function sendBirthdayNotifications(Request $request)
    {
        // Vérifier la clé secrète
        if (!$this->secretIsValid($request)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $firebaseService = new FirebaseService();
        $now = Carbon::now();
        $tomorrow = $now->copy()->addDay()->format('m-d');

        $results = [
            'time' => $now->toDateTimeString(),
            'birthdays_checked' => 0,
            'notifications_sent' => 0,
            'tests_sent' => 0,
        ];

        // 1. Récupérer les anniversaires de DEMAIN (pour envoyer rappel AVANT minuit)
        $birthdays = FriendBirthday::where('is_active', true)
            ->where('notification_sent_this_year', false)
            ->whereRaw("DATE_FORMAT(birthday_date, '%m-%d') = ?", [$tomorrow])
            ->with('user')
            ->get();

        $results['birthdays_checked'] = $birthdays->count();

        foreach ($birthdays as $birthday) {
            // Calculer l'heure de notification: 00h00 du jour anniversaire MOINS reminder_minutes_before
            $reminderMinutesBefore = $birthday->reminder_minutes_before ?? 60; // 1h par défaut

            // Minuit du jour de l'anniversaire (demain)
            $birthdayMidnight = $now->copy()->addDay()->startOfDay();

            // Heure de notification = minuit - X minutes
            $notificationTime = $birthdayMidnight->copy()->subMinutes($reminderMinutesBefore);

            // Vérifier si c'est l'heure d'envoyer (tolérance de 5 minutes)
            $diffMinutes = abs($now->diffInMinutes($notificationTime, false));

            if ($diffMinutes > 5) {
                continue; // Pas encore l'heure
            }

            $user = $birthday->user;
            if (!$user || !$user->fcm_token) {
                continue;
            }

            // Message adapté selon le temps restant
            $hoursUntil = floor($reminderMinutesBefore / 60);
            $minutesRemaining = $reminderMinutesBefore % 60;

            $timeText = '';
            if ($hoursUntil > 0 && $minutesRemaining > 0) {
                $timeText = "dans {$hoursUntil}h{$minutesRemaining}";
            } elseif ($hoursUntil > 0) {
                $timeText = "dans {$hoursUntil} heure" . ($hoursUntil > 1 ? 's' : '');
            } else {
                $timeText = "dans {$minutesRemaining} minute" . ($minutesRemaining > 1 ? 's' : '');
            }

            $title = "🎂 Anniversaire de {$birthday->friend_name} {$timeText} !";
            $body = "{$birthday->friend_name} soufflera une bougie de plus {$timeText}. Sois prêt 😎";

            $result = $firebaseService->sendNotification(
                $user->fcm_token,
                $title,
                $body,
                [
                    'type' => 'birthday_reminder',
                    'birthday_id' => $birthday->id,
                    'friend_name' => $birthday->friend_name,
                ]
            );

            if ($result) {
                $birthday->notification_sent_this_year = true;
                $birthday->save();

                // Créer la notification dans l'app
                Notification::create([
                    'user_id' => $user->id,
                    'titre' => $title,
                    'contenus' => $body,
                    'type' => 'birthday',
                ]);

                $results['notifications_sent']++;

                Log::info("Birthday notification sent via cron", [
                    'birthday_id' => $birthday->id,
                    'friend_name' => $birthday->friend_name,
                    'user_id' => $user->id,
                    'notification_time' => $notificationTime->toDateTimeString(),
                ]);
            }
        }

        // 2. Envoyer les tests programmés
        $pendingTests = Cache::get('pending_birthday_tests', []);
        $remainingTests = [];

        foreach ($pendingTests as $cacheKey) {
            $testData = Cache::get($cacheKey);

            if (!$testData) {
                continue;
            }

            $scheduledAt = Carbon::parse($testData['scheduled_at']);

            if ($now->greaterThanOrEqualTo($scheduledAt)) {
                $result = $firebaseService->sendNotification(
                    $testData['fcm_token'],
                    '🎂 Anniversaire de Test Ami !',
                    'Test Ami soufflera une bougie de plus dans quelques instants. Sois prêt 😎',
                    ['type' => 'birthday_scheduled_test']
                );

                if ($result) {
                    // Créer la notification dans l'app
                    Notification::create([
                        'user_id' => $testData['user_id'],
                        'titre' => '🎂 Anniversaire de Test Ami !',
                        'contenus' => 'Test Ami soufflera une bougie de plus dans quelques instants. Sois prêt 😎',
                        'type' => 'birthday_test',
                    ]);

                    $results['tests_sent']++;

                    Log::info("Scheduled test sent via cron", [
                        'user_id' => $testData['user_id'],
                    ]);
                }

                Cache::forget($cacheKey);
            } else {
                $remainingTests[] = $cacheKey;
            }
        }

        Cache::put('pending_birthday_tests', $remainingTests, 86400);

        // 3. Réinitialiser les flags le 1er janvier
        if ($now->month === 1 && $now->day === 1 && $now->hour === 0) {
            FriendBirthday::query()->update(['notification_sent_this_year' => false]);
            $results['reset_flags'] = true;
        }

        return response()->json([
            'status' => true,
            'message' => 'Cron executed successfully',
            'results' => $results,
        ]);
    }

    /**
     * Endpoint pour cron-job.org - Supprimer les notifications de plus de 7 jours.
     * Miroir de la tâche planifiée `notifications:prune-old` (fiabilité Hostinger).
     */
    public function pruneOldNotifications(Request $request)
    {
        if (!$this->secretIsValid($request)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        \Illuminate\Support\Facades\Artisan::call('notifications:prune-old');

        return response()->json([
            'status' => true,
            'message' => 'Notifications purgées',
            'output' => trim(\Illuminate\Support\Facades\Artisan::output()),
        ]);
    }

    /**
     * Forcer l'envoi d'une notification pour le premier anniversaire actif (test)
     */
    public function testRealBirthday(Request $request)
    {
        // Vérifier la clé secrète
        if (!$this->secretIsValid($request)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $firebaseService = new FirebaseService();

        // Trouver le premier anniversaire actif avec un utilisateur qui a un token FCM
        $birthday = FriendBirthday::where('is_active', true)
            ->whereHas('user', function ($query) {
                $query->whereNotNull('fcm_token');
            })
            ->with('user')
            ->first();

        if (!$birthday) {
            return response()->json([
                'status' => false,
                'message' => 'Aucun anniversaire actif trouvé avec un utilisateur ayant un token FCM'
            ], 404);
        }

        $user = $birthday->user;
        $title = "🎂 Anniversaire de {$birthday->friend_name} !";
        $body = "{$birthday->friend_name} soufflera une bougie de plus dans quelques instants. Sois prêt 😎";

        $result = $firebaseService->sendNotification(
            $user->fcm_token,
            $title,
            $body,
            [
                'type' => 'birthday_reminder',
                'birthday_id' => $birthday->id,
                'friend_name' => $birthday->friend_name,
            ]
        );

        if ($result) {
            // Créer la notification dans l'app
            Notification::create([
                'user_id' => $user->id,
                'titre' => $title,
                'contenus' => $body,
                'type' => 'birthday',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Notification envoyée avec succès',
                'birthday' => [
                    'id' => $birthday->id,
                    'friend_name' => $birthday->friend_name,
                    'user' => $user->nom,
                ],
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Échec de l\'envoi de la notification',
            'debug' => [
                'birthday_id' => $birthday->id,
                'friend_name' => $birthday->friend_name,
                'user_id' => $user->id,
                'user_name' => $user->nom,
                'fcm_token_length' => strlen($user->fcm_token ?? ''),
            ]
        ], 500);
    }
}
