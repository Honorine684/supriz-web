<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageReaction;
use App\Models\SousCategorie;
use App\Models\Musique;
use App\Models\ArrierePlan;
use App\Models\Notification;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ApiMessageController extends Controller
{
    /**
     * Récupérer tous les messages de l'utilisateur connecté
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $messages = Message::where('user_id', $user->id)
                ->with(['sousCategorie', 'musique'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($message) {
                    // Récupérer les réactions groupées par emoji avec une requête SQL
                    $reactionsGrouped = MessageReaction::where('message_id', $message->id)
                        ->selectRaw('emoji, count(*) as count')
                        ->groupBy('emoji')
                        ->get()
                        ->mapWithKeys(function ($item) {
                            return [$item->emoji => $item->count];
                        });

                    return [
                        'id' => $message->id,
                        'titre' => $message->titre,
                        'contenu' => $message->contenu,
                        'slug' => $message->slug,
                        'share_url' => url('/message/' . $message->slug),
                        'categorie' => $message->sousCategorie->name_sous_categories ?? null,
                        'media_url' => $message->media_path ? asset('storage/' . $message->media_path) : null,
                        'background_path' => $message->background_path,
                        'images' => $message->images,
                        'musique' => $message->musique ? [
                            'id' => $message->musique->id,
                            'titre' => $message->musique->titre,
                            'artiste' => $message->musique->artiste,
                            'fichier_url' => $message->musique->fichier_audio ? asset('storage/' . $message->musique->fichier_audio) : $message->musique->api_url,
                        ] : null,
                        'expiration_type' => $message->expiration_type,
                        'date_expiration' => $message->date_expiration->format('d/m/Y H:i'),
                        'is_expired' => $message->isExpired(),
                        'is_active' => $message->is_active,
                        'views_count' => $message->views_count,
                        'reactions' => $reactionsGrouped,
                        'created_at' => $message->created_at->format('d/m/Y H:i'),
                    ];
                });

            return response()->json([
                'status' => true,
                'messages' => $messages,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Créer un nouveau message
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'titre' => 'required|string|max:60',
                'contenu' => 'nullable|string|max:500',
                'sous_categorie_id' => 'required|exists:homme_sous_categories,id',
                'media' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:10240',
                'images' => 'nullable|array',
                'images.*' => 'file|mimes:jpg,jpeg,png,gif|max:10240',
                'background_path' => 'nullable|string',
                'musique_id' => 'nullable|exists:musiques,id',
                'expiration' => 'nullable|string|in:24h,48h,72h,7d,30d',
                'max_views' => 'nullable|integer|min:1',
                'unique_view' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            $mediaPath = null;
            $mediaType = null;
            $uploadedImages = [];

            // Upload du média principal
            if ($request->hasFile('media')) {
                $file = $request->file('media');
                $mediaType = 'image';
                $mediaPath = $file->store('messages/images', 'public');
            }

            // Upload des images multiples
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('messages/images', 'public');
                    $uploadedImages[] = asset('storage/' . $path);
                }
            }

            // Calculer la date d'expiration
            $expiration = $request->expiration ?? '24h';
            $dateExpiration = Carbon::now();

            switch ($expiration) {
                case '48h':
                    $dateExpiration->addHours(48);
                    break;
                case '72h':
                    $dateExpiration->addHours(72);
                    break;
                case '7d':
                    $dateExpiration->addDays(7);
                    break;
                case '30d':
                    $dateExpiration->addDays(30);
                    break;
                default:
                    $dateExpiration->addHours(24);
            }

            // Créer le message
            $message = Message::create([
                'user_id' => $user->id,
                'titre' => $request->titre,
                'contenu' => $request->contenu,
                'sous_categorie_id' => $request->sous_categorie_id,
                'media_path' => $mediaPath,
                'media_type' => $mediaType,
                'background_path' => $request->background_path,
                'images' => !empty($uploadedImages) ? $uploadedImages : null,
                'musique_id' => $request->musique_id,
                'date_expiration' => $dateExpiration,
                'expiration_type' => $expiration,
                'is_active' => true,
                'max_views' => $request->max_views,
                'unique_view' => $request->unique_view ?? false,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Message créé avec succès',
                'data' => [
                    'id' => $message->id,
                    'slug' => $message->slug,
                    'share_url' => url('/message/' . $message->slug),
                    'titre' => $message->titre,
                    'date_expiration' => $message->date_expiration->format('d/m/Y H:i'),
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
     * Récupérer un message spécifique
     */
    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();

            $message = Message::where('id', $id)
                ->where('user_id', $user->id)
                ->with(['sousCategorie', 'musique'])
                ->first();

            if (!$message) {
                return response()->json([
                    'status' => false,
                    'message' => 'Message non trouvé'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $message->id,
                    'titre' => $message->titre,
                    'contenu' => $message->contenu,
                    'slug' => $message->slug,
                    'share_url' => url('/message/' . $message->slug),
                    'categorie' => $message->sousCategorie->name_sous_categories ?? null,
                    'media_url' => $message->media_path ? asset('storage/' . $message->media_path) : null,
                    'background_path' => $message->background_path,
                    'images' => $message->images,
                    'musique' => $message->musique ? [
                        'id' => $message->musique->id,
                        'titre' => $message->musique->titre,
                        'artiste' => $message->musique->artiste,
                        'fichier_url' => $message->musique->fichier_audio ? asset('storage/' . $message->musique->fichier_audio) : $message->musique->api_url,
                    ] : null,
                    'expiration_type' => $message->expiration_type,
                    'date_expiration' => $message->date_expiration->format('d/m/Y H:i'),
                    'is_expired' => $message->isExpired(),
                    'is_active' => $message->is_active,
                    'views_count' => $message->views_count,
                    'created_at' => $message->created_at->format('d/m/Y H:i'),
                ]
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un message
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();

            $message = Message::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$message) {
                return response()->json([
                    'status' => false,
                    'message' => 'Message non trouvé'
                ], 404);
            }

            // Supprimer les fichiers associés
            if ($message->media_path) {
                Storage::disk('public')->delete($message->media_path);
            }

            $message->delete();

            return response()->json([
                'status' => true,
                'message' => 'Message supprimé avec succès'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Activer/Désactiver un message
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            $user = $request->user();

            $message = Message::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$message) {
                return response()->json([
                    'status' => false,
                    'message' => 'Message non trouvé'
                ], 404);
            }

            $message->is_active = !$message->is_active;
            $message->save();

            return response()->json([
                'status' => true,
                'message' => $message->is_active ? 'Message activé' : 'Message désactivé',
                'is_active' => $message->is_active
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les catégories Supriz
     */
    public function getCategories()
    {
        try {
            $suprizCategorie = \App\Models\Categorie::where('name_id_categories', 's')->first();

            $categories = SousCategorie::where('type_categorie_id', $suprizCategorie->id ?? null)
                ->where('is_active', true)
                ->orderByRaw("CASE
                    WHEN name_sous_categories = 'Anniversaire' THEN 1
                    WHEN name_sous_categories = 'Mariage' THEN 2
                    WHEN name_sous_categories = 'Vœux' THEN 3
                    WHEN name_sous_categories = 'Nouvel an' THEN 4
                    WHEN name_sous_categories = 'Noël' THEN 5
                    ELSE 6
                END, name_sous_categories ASC")
                ->get(['id', 'name_sous_categories', 'icone', 'image', 'placeholder_texte', 'gift_image'])
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name_sous_categories' => $category->name_sous_categories,
                        'icone' => $category->icone ? asset('storage/' . $category->icone) : null,
                        'image' => $category->image ? asset('storage/' . $category->image) : null,
                        'placeholder_texte' => $category->placeholder_texte,
                        'gift_image' => $category->gift_image ? asset('storage/' . $category->gift_image) : null,
                    ];
                });

            return response()->json([
                'status' => true,
                'categories' => $categories
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les musiques par catégorie
     */
    public function getMusiques($categorieId = null)
    {
        try {
            $query = Musique::where('is_active', true);

            if ($categorieId) {
                $query->where('sous_categorie_id', $categorieId);
            }

            // Debug: log les IDs des catégories
            \Log::info('Musiques request - categorieId: ' . ($categorieId ?? 'all'));

            $musiques = $query->orderBy('created_at', 'desc')->get()->map(function ($musique) {
                return [
                    'id' => $musique->id,
                    'titre' => $musique->titre,
                    'artiste' => null,
                    'fichier_url' => $musique->fichier_audio ? asset('storage/' . $musique->fichier_audio) : $musique->api_url,
                    'duree' => $musique->duree,
                ];
            });

            return response()->json([
                'status' => true,
                'musiques' => $musiques
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les arrière-plans par catégorie
     */
    public function getArrierePlans($categorieId = null)
    {
        try {
            $query = ArrierePlan::where('is_active', true);

            if ($categorieId) {
                $query->where('sous_categorie_id', $categorieId);
            }

            $arrierePlans = $query->orderBy('ordre')->get()->map(function ($ap) {
                return [
                    'id' => $ap->id,
                    'nom' => $ap->nom,
                    'image_url' => $ap->image ? asset('storage/' . $ap->image) : null,
                    'ordre' => $ap->ordre,
                ];
            });

            return response()->json([
                'status' => true,
                'arriere_plans' => $arrierePlans
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Voir un message publiquement via son slug
     */
    public function showPublic(Request $request, $slug)
    {
        try {
            $message = Message::where('slug', $slug)
                ->with(['sousCategorie', 'musique', 'user', 'reactions'])
                ->first();

            if (!$message) {
                return response()->json([
                    'status' => false,
                    'message' => 'Message non trouvé'
                ], 404);
            }

            // Vérifier si le message est expiré
            if ($message->isExpired()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ce message a expiré',
                    'is_expired' => true
                ], 410);
            }

            // Vérifier si le message est actif
            if (!$message->is_active) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ce message n\'est plus disponible',
                    'is_inactive' => true
                ], 410);
            }

            // Vérifier si le max_views est atteint
            if ($message->hasReachedMaxViews()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ce message a atteint le nombre maximum de vues',
                    'max_views_reached' => true
                ], 410);
            }

            $viewerIp = $request->ip();
            $deviceId = $request->header('X-Device-Id');

            // Vérifier si la vue unique est activée
            if ($message->unique_view) {
                // Une seule personne peut voir ce message
                if ($message->views()->exists()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Ce message a déjà été vu',
                        'already_viewed' => true
                    ], 410);
                }
            }

            // Enregistrer la vue (retourne null si déjà vue)
            $viewRecorded = $message->recordView($viewerIp, $deviceId);

            // Incrémenter et notifier seulement si c'est une nouvelle vue
            if ($viewRecorded) {
                $message->incrementViews();
                // Envoyer une notification push au créateur
                $this->sendViewNotificationToOwner($message);
            }

            // Récupérer les réactions groupées par emoji
            $reactionsGrouped = $message->reactions()
                ->selectRaw('emoji, count(*) as count')
                ->groupBy('emoji')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->emoji => $item->count];
                });

            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $message->id,
                    'titre' => $message->titre,
                    'contenu' => $message->contenu,
                    'categorie' => $message->sousCategorie->name_sous_categories ?? null,
                    'media_url' => $message->media_path ? asset('storage/' . $message->media_path) : null,
                    'background_path' => $message->background_path,
                    'images' => $message->images,
                    'musique' => $message->musique ? [
                        'titre' => $message->musique->titre,
                        'artiste' => $message->musique->artiste,
                        'fichier_url' => $message->musique->fichier_audio ? asset('storage/' . $message->musique->fichier_audio) : $message->musique->api_url,
                    ] : null,
                    'views_count' => $message->views_count,
                    'reactions' => $reactionsGrouped,
                    'can_react' => true,
                ]
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Ajouter une réaction à un message
     */
    public function addReaction(Request $request, $slug)
    {
        try {
            $validator = Validator::make($request->all(), [
                'emoji' => 'required|string|max:10',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Emoji invalide',
                    'errors' => $validator->errors()
                ], 422);
            }

            $message = Message::where('slug', $slug)->with('user')->first();

            if (!$message) {
                return response()->json([
                    'status' => false,
                    'message' => 'Message non trouvé'
                ], 404);
            }

            // Vérifier si le message est expiré ou inactif
            if ($message->isExpired() || !$message->is_active) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ce message n\'est plus disponible'
                ], 410);
            }

            $viewerIp = $request->ip();
            $deviceId = $request->header('X-Device-Id');

            // Vérifier si l'utilisateur a déjà réagi (avec n'importe quel emoji)
            $existingReaction = MessageReaction::where('message_id', $message->id)
                ->where(function ($query) use ($viewerIp, $deviceId) {
                    if ($deviceId) {
                        $query->where('device_id', $deviceId);
                    } else {
                        $query->where('viewer_ip', $viewerIp);
                    }
                })
                ->first();

            if ($existingReaction) {
                if ($existingReaction->emoji === $request->emoji) {
                    // Même emoji = supprimer la réaction (toggle off)
                    $existingReaction->delete();
                    $action = 'removed';
                } else {
                    // Emoji différent = changer la réaction
                    $existingReaction->emoji = $request->emoji;
                    $existingReaction->save();
                    $action = 'changed';

                    // Envoyer une notification pour la nouvelle réaction
                    $this->sendReactionNotificationToOwner($message, $request->emoji, true);
                }
            } else {
                // Ajouter la réaction (première fois)
                MessageReaction::create([
                    'message_id' => $message->id,
                    'emoji' => $request->emoji,
                    'viewer_ip' => $viewerIp,
                    'device_id' => $deviceId,
                ]);
                $action = 'added';

                // Envoyer une notification push au créateur
                $this->sendReactionNotificationToOwner($message, $request->emoji, false);
            }

            // Récupérer les réactions mises à jour
            $reactionsGrouped = $message->reactions()
                ->selectRaw('emoji, count(*) as count')
                ->groupBy('emoji')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->emoji => $item->count];
                });

            return response()->json([
                'status' => true,
                'message' => $action === 'added' ? 'Réaction ajoutée' : 'Réaction retirée',
                'action' => $action,
                'reactions' => $reactionsGrouped,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Envoyer une notification de vue au propriétaire du message
     */
    private function sendViewNotificationToOwner($message)
    {
        try {
            $user = $message->user;
            if (!$user) return;

            // Créer une notification dans la base de données
            Notification::create([
                'contenus' => "Quelqu'un a vu votre message \"{$message->titre}\"",
            ]);

            // Envoyer la notification push via Firebase
            $firebaseService = new FirebaseService();
            $firebaseService->sendViewNotification($user, $message);
        } catch (\Exception $e) {
            \Log::error('Error sending view notification: ' . $e->getMessage());
        }
    }

    /**
     * Envoyer une notification de réaction au propriétaire du message
     */
    private function sendReactionNotificationToOwner($message, $emoji, $isChange = false)
    {
        try {
            $user = $message->user;
            if (!$user) return;

            $notificationText = "Une réaction {$emoji} vient d'arriver… ton message a fait son effet !";

            // Créer une notification dans la base de données
            Notification::create([
                'contenus' => $notificationText,
            ]);

            // Envoyer la notification push via Firebase
            $firebaseService = new FirebaseService();
            $firebaseService->sendReactionNotification($user, $message, $emoji);
        } catch (\Exception $e) {
            \Log::error('Error sending reaction notification: ' . $e->getMessage());
        }
    }
}
