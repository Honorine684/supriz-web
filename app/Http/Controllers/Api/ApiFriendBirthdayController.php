<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FriendBirthday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\Services\FirebaseService;
use App\Models\Notification;
use Carbon\Carbon;

class ApiFriendBirthdayController extends Controller
{
    /**
     * Liste tous les anniversaires de l'utilisateur
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $birthdays = FriendBirthday::where('user_id', $user->id)
                ->orderByRaw("DATE_FORMAT(birthday_date, '%m-%d') ASC")
                ->get()
                ->map(function ($birthday) {
                    return [
                        'id' => $birthday->id,
                        'friend_name' => $birthday->friend_name,
                        'friend_phone' => $birthday->friend_phone,
                        'birthday_date' => $birthday->birthday_date->format('Y-m-d'),
                        'birthday_display' => $birthday->birthday_date->format('d M'),
                        'reminder_time' => $birthday->reminder_time,
                        'reminder_minutes_before' => $birthday->reminder_minutes_before,
                        'is_active' => $birthday->is_active,
                        'notes' => $birthday->notes,
                        'days_until' => $birthday->days_until_birthday,
                        'is_today' => $birthday->isBirthdayToday(),
                        'next_birthday' => $birthday->next_birthday->format('Y-m-d'),
                    ];
                });

            return response()->json([
                'status' => true,
                'birthdays' => $birthdays,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Anniversaires à venir (7 prochains jours)
     */
    public function upcoming(Request $request)
    {
        try {
            $user = $request->user();
            $days = $request->input('days', 7);

            $birthdays = FriendBirthday::where('user_id', $user->id)
                ->where('is_active', true)
                ->get()
                ->filter(function ($birthday) use ($days) {
                    $daysUntil = $birthday->days_until_birthday;
                    return $daysUntil >= 0 && $daysUntil <= $days;
                })
                ->sortBy('days_until_birthday')
                ->values()
                ->map(function ($birthday) {
                    return [
                        'id' => $birthday->id,
                        'friend_name' => $birthday->friend_name,
                        'friend_phone' => $birthday->friend_phone,
                        'birthday_display' => $birthday->birthday_date->format('d M'),
                        'days_until' => $birthday->days_until_birthday,
                        'is_today' => $birthday->isBirthdayToday(),
                    ];
                });

            return response()->json([
                'status' => true,
                'upcoming' => $birthdays,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Anniversaires aujourd'hui
     */
    public function today(Request $request)
    {
        try {
            $user = $request->user();

            $birthdays = FriendBirthday::where('user_id', $user->id)
                ->where('is_active', true)
                ->get()
                ->filter(function ($birthday) {
                    return $birthday->isBirthdayToday();
                })
                ->values()
                ->map(function ($birthday) {
                    return [
                        'id' => $birthday->id,
                        'friend_name' => $birthday->friend_name,
                        'friend_phone' => $birthday->friend_phone,
                    ];
                });

            return response()->json([
                'status' => true,
                'today' => $birthdays,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Ajouter un anniversaire
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'friend_name' => 'required|string|max:255',
                'friend_phone' => 'nullable|string|max:20',
                'birthday_date' => 'required|date',
                'reminder_time' => 'nullable|date_format:H:i',
                'reminder_minutes_before' => 'nullable|integer|min:0|max:180',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();

            $birthday = FriendBirthday::create([
                'user_id' => $user->id,
                'friend_name' => $request->friend_name,
                'friend_phone' => $request->friend_phone,
                'birthday_date' => $request->birthday_date,
                'reminder_time' => $request->reminder_time, // Plus utilisé mais gardé pour compatibilité
                'reminder_minutes_before' => $request->reminder_minutes_before ?? 60, // 1h avant minuit par défaut
                'notes' => $request->notes,
                'is_active' => true,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Anniversaire ajouté avec succès',
                'data' => [
                    'id' => $birthday->id,
                    'friend_name' => $birthday->friend_name,
                    'birthday_date' => $birthday->birthday_date->format('Y-m-d'),
                    'days_until' => $birthday->days_until_birthday,
                ]
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Modifier un anniversaire
     */
    public function update(Request $request, $id)
    {
        try {
            $user = $request->user();

            $birthday = FriendBirthday::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$birthday) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anniversaire non trouvé'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'friend_name' => 'sometimes|string|max:255',
                'friend_phone' => 'nullable|string|max:20',
                'birthday_date' => 'sometimes|date',
                'reminder_time' => 'nullable|date_format:H:i',
                'reminder_minutes_before' => 'nullable|integer|min:0|max:180',
                'is_active' => 'sometimes|boolean',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $birthday->update($request->only([
                'friend_name',
                'friend_phone',
                'birthday_date',
                'reminder_time',
                'reminder_minutes_before',
                'is_active',
                'notes',
            ]));

            return response()->json([
                'status' => true,
                'message' => 'Anniversaire modifié avec succès',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un anniversaire
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();

            $birthday = FriendBirthday::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$birthday) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anniversaire non trouvé'
                ], 404);
            }

            $birthday->delete();

            return response()->json([
                'status' => true,
                'message' => 'Anniversaire supprimé avec succès'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Tester l'envoi de notification pour un anniversaire spécifique
     */
    public function testNotification(Request $request, $id)
    {
        try {
            $user = $request->user();

            $birthday = FriendBirthday::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$birthday) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anniversaire non trouvé'
                ], 404);
            }

            if (!$user->fcm_token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Pas de token FCM configuré. Reconnectez-vous à l\'application.'
                ], 400);
            }

            $firebaseService = new FirebaseService();

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
                    'friend_phone' => $birthday->friend_phone ?? '',
                ]
            );

            if ($result) {
                // Créer la notification dans l'app
                Notification::create([
                    'user_id' => $user->id,
                    'titre' => $title,
                    'contenus' => $body,
                    'type' => 'birthday_test',
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Notification de test envoyée avec succès'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Échec de l\'envoi de la notification'
                ], 500);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Tester une notification immédiate (sans anniversaire spécifique)
     */
    public function testImmediateNotification(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user->fcm_token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Pas de token FCM configuré. Reconnectez-vous à l\'application.'
                ], 400);
            }

            $firebaseService = new FirebaseService();

            $result = $firebaseService->sendNotification(
                $user->fcm_token,
                '🎂 Anniversaire de Test Ami !',
                'Test Ami soufflera une bougie de plus dans quelques instants. Sois prêt 😎',
                [
                    'type' => 'birthday_test',
                ]
            );

            if ($result) {
                // Créer la notification dans l'app
                Notification::create([
                    'user_id' => $user->id,
                    'titre' => '🎂 Anniversaire de Test Ami !',
                    'contenus' => 'Test Ami soufflera une bougie de plus dans quelques instants. Sois prêt 😎',
                    'type' => 'birthday_test',
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Notification de test envoyée avec succès'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Échec de l\'envoi de la notification'
                ], 500);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Programmer une notification de test dans X minutes
     */
    public function scheduleTestNotification(Request $request)
    {
        try {
            $user = $request->user();
            $minutes = $request->input('minutes', 5);

            if (!$user->fcm_token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Pas de token FCM configuré. Reconnectez-vous à l\'application.'
                ], 400);
            }

            // Stocker le test programmé dans le cache
            $scheduledTime = Carbon::now()->addMinutes($minutes);
            $cacheKey = 'birthday_test_' . $user->id . '_' . time();

            Cache::put($cacheKey, [
                'user_id' => $user->id,
                'fcm_token' => $user->fcm_token,
                'scheduled_at' => $scheduledTime->toDateTimeString(),
                'user_name' => $user->nom,
            ], $minutes * 60 + 300); // Expire 5 min après l'heure prévue

            // Ajouter à la liste des tests en attente
            $pendingTests = Cache::get('pending_birthday_tests', []);
            $pendingTests[] = $cacheKey;
            Cache::put('pending_birthday_tests', $pendingTests, 86400); // 24h

            return response()->json([
                'status' => true,
                'message' => "Notification programmée dans {$minutes} minute(s)",
                'scheduled_at' => $scheduledTime->format('H:i'),
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
