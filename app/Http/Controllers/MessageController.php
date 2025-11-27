<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\SousCategorie;
use App\Models\Musique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MessageController extends Controller
{
    /**
     * Afficher la page d'accueil publique
     */
    public function welcome()
    {
        $suprizCategorie = \App\Models\Categorie::where('name_id_categories', 's')->first();

        // Récupérer les catégories prédéfinies pour la section "Choisis ton occasion"
        $categoriesSuprizPredefinies = ['Anniversaire', 'Mariage', 'Vœux', 'Nouvel an', 'Noël'];
        $categoriesSupriz = SousCategorie::where('type_categorie_id', $suprizCategorie->id ?? null)
            ->whereIn('name_sous_categories', $categoriesSuprizPredefinies)
            ->orderByRaw("FIELD(name_sous_categories, 'Anniversaire', 'Mariage', 'Vœux', 'Nouvel an', 'Noël')")
            ->get();

        // Récupérer les FAQs
        $faqs = \App\Models\Faq::orderBy('created_at', 'desc')->get();

        return view('welcome', compact('categoriesSupriz', 'faqs'));
    }

    /**
     * Afficher la page de création de message
     */
    public function index()
    {
        $suprizCategorie = \App\Models\Categorie::where('name_id_categories', 's')->first();

        // Récupérer TOUTES les catégories Supriz actives
        // Catégories prédéfinies affichées en premier, puis les autres par ordre alphabétique
        $categoriesSuprizPredefinies = ['Anniversaire', 'Mariage', 'Vœux', 'Nouvel an', 'Noël'];
        $categoriesSupriz = SousCategorie::where('type_categorie_id', $suprizCategorie->id ?? null)
            ->where('is_active', true)
            ->orderByRaw("CASE
                WHEN name_sous_categories = 'Anniversaire' THEN 1
                WHEN name_sous_categories = 'Mariage' THEN 2
                WHEN name_sous_categories = 'Vœux' THEN 3
                WHEN name_sous_categories = 'Nouvel an' THEN 4
                WHEN name_sous_categories = 'Noël' THEN 5
                ELSE 6
            END, name_sous_categories ASC")
            ->get();

        $musiques = Musique::where('is_active', true)->get();

        return view('nouveau-message', compact('categoriesSupriz', 'musiques'));
    }

    /**
     * Créer un nouveau message
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:60',
            'contenu' => 'nullable|string|max:500',
            'sous_categorie_id' => 'required|exists:homme_sous_categories,id',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:10240',
            'musique_id' => 'nullable|exists:musiques,id',
            'expiration' => 'nullable|string',
        ]);

        $mediaPath = null;
        $mediaType = null;

        // Upload du media
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $extension = $file->getClientOriginalExtension();

            // Déterminer le type de media
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $videoExtensions = ['mp4', 'mov', 'avi'];

            if (in_array(strtolower($extension), $imageExtensions)) {
                $mediaType = 'image';
                $mediaPath = $file->store('messages/images', 'public');
            } elseif (in_array(strtolower($extension), $videoExtensions)) {
                $mediaType = 'video';
                $mediaPath = $file->store('messages/videos', 'public');
            }
        }

        // Calculer la date d'expiration
        $expiration = $request->expiration ?? '24h';
        $dateExpiration = Carbon::now();

        if (str_contains($expiration, 'h')) {
            $hours = (int) str_replace('h', '', $expiration);
            $dateExpiration->addHours($hours);
        } elseif (str_contains($expiration, 'd')) {
            $days = (int) str_replace('d', '', $expiration);
            $dateExpiration->addDays($days);
        } else {
            $dateExpiration->addHours(24); // Par défaut 24h
        }

        // Créer le message
        $message = Message::create([
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'sous_categorie_id' => $request->sous_categorie_id,
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
            'musique_id' => $request->musique_id,
            'date_expiration' => $dateExpiration,
            'is_active' => true,
        ]);

        // Générer l'URL complète du message
        $messageUrl = url('/message/' . $message->slug);

        return response()->json([
            'success' => true,
            'message' => 'Message créé avec succès!',
            'data' => [
                'id' => $message->id,
                'slug' => $message->slug,
                'url' => $messageUrl,
                'titre' => $message->titre,
                'contenu' => $message->contenu,
                'media_url' => $message->media_path ? asset('storage/' . $message->media_path) : null,
                'media_type' => $message->media_type,
                'musique' => $message->musique,
                'categorie' => $message->sousCategorie->name_sous_categories,
                'date_expiration' => $message->date_expiration->format('d/m/Y'),
                'is_active' => $message->is_active,
            ]
        ]);
    }

    /**
     * Afficher un message via son slug (page publique)
     */
    public function show($slug)
    {
        $message = Message::where('slug', $slug)->firstOrFail();

        // Vérifier si le message est expiré
        if ($message->isExpired()) {
            return view('message-expired', compact('message'));
        }

        // Vérifier si le message est actif
        if (!$message->is_active) {
            return view('message-inactive', compact('message'));
        }

        // Incrémenter le compteur de vues
        $message->incrementViews();

        return view('message-view', compact('message'));
    }

    /**
     * Récupérer les catégories Supriz actives (API)
     */
    public function getCategoriesSupriz()
    {
        $suprizCategorie = \App\Models\Categorie::where('name_id_categories', 's')->first();

        // Récupérer TOUTES les catégories actives
        // Catégories prédéfinies en premier, puis les autres par ordre alphabétique
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
            ->get(['id', 'name_sous_categories', 'icone', 'image', 'placeholder_texte']);

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    /**
     * Activer/Désactiver un message
     */
    public function toggleStatus($id)
    {
        $message = Message::findOrFail($id);
        $message->is_active = !$message->is_active;
        $message->save();

        return response()->json([
            'success' => true,
            'is_active' => $message->is_active
        ]);
    }
}
