<?php
namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Achat;
use App\Models\Commandes;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use FedaPay\FedaPay;
use App\Models\Historique;
use App\Mail\OrderConfirmation;
use App\Mail\MerchantOrderNotification;
class PanierController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Configure le SDK FedaPay depuis la config (jamais de clé en dur).
     */
    private function configureFedaPay(): void
    {
        FedaPay::setApiKey(config('fedapay.secret_key'));
        FedaPay::setEnvironment(config('fedapay.environment', 'sandbox'));
    }

    /**
     * Calcule le montant total d'une commande CÔTÉ SERVEUR (produits + livraison),
     * en une seule requête. Ne jamais faire confiance au montant envoyé par le client.
     */
    private function computeOrderAmount(Commandes $commande): int
    {
        $achats = Achat::where('commande_id', $commande->id)->get();
        $productIds = $achats->pluck('product_id')->unique()->toArray();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $total = $achats->reduce(function ($carry, $achat) use ($products) {
            $produit = $products->get($achat->product_id);
            if (!$produit) {
                return $carry;
            }
            $prixUnitaire = $produit->prix_promo ?: $produit->price;
            return $carry + ($prixUnitaire * $achat->qte_achat);
        }, 0);

        return (int) round($total) + $this->computeDeliveryFee($commande);
    }

    /**
     * Frais de livraison de la commande, basés sur la distance marchand →
     * adresse de livraison (une course par marchand, estimation Gozem :
     * 500 FCFA jusqu'à 2 km, puis +100 FCFA/km, distance vol d'oiseau × 1,3).
     * Repli sur les prix_livraison par produit quand les coordonnées du
     * marchand ou de la livraison sont absentes.
     * Doit rester aligné avec DeliveryFeeCalculator côté app Flutter.
     */
    private function computeDeliveryFee(Commandes $commande): int
    {
        $achats = Achat::where('commande_id', $commande->id)->get();
        $productIds = $achats->pluck('product_id')->unique()->toArray();
        $products = Product::whereIn('id', $productIds)->with('marchand')->get()->keyBy('id');

        $hasDest = $commande->latitude_livraison !== null
            && $commande->longitude_livraison !== null;

        $total = 0;
        $marchandsFactures = [];
        foreach ($achats as $achat) {
            $produit = $products->get($achat->product_id);
            if (!$produit) {
                continue;
            }
            $marchand = $produit->marchand;
            if ($hasDest && $marchand
                && $marchand->latitude !== null && $marchand->longitude !== null) {
                if (isset($marchandsFactures[$marchand->id])) {
                    continue; // une seule course par marchand
                }
                $marchandsFactures[$marchand->id] = true;
                $total += $this->distanceDeliveryFee(
                    (float) $marchand->latitude,
                    (float) $marchand->longitude,
                    (float) $commande->latitude_livraison,
                    (float) $commande->longitude_livraison,
                );
            } else {
                $total += $produit->prix_livraison ?? 0;
            }
        }

        return (int) round($total);
    }

    /**
     * Tarif d'une course : Haversine × 1,3 ; 500 FCFA ≤ 2 km, puis +100 FCFA/km.
     */
    private function distanceDeliveryFee(float $lat1, float $lng1, float $lat2, float $lng2): int
    {
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $km = 6371 * 2 * atan2(sqrt($a), sqrt(1 - $a));
        $routeKm = $km * 1.3;
        if ($routeKm <= 2) {
            return 500;
        }
        return (int) round(500 + ($routeKm - 2) * 100);
    }

    /**
     * Ajouter un produit au panier.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request)
    {
        try {
            // Validation des paramètres
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'qte_achat' => 'required|integer|min:1',
                'description_de_personnalisation' => 'nullable|string',
            ]);
    
            $userId = Auth::id();
    
            // Vérifier s'il y a une commande en cours
            $commande = Commandes::where('users_id', $userId)
                                 ->where('status', 'encours')
                                 ->first();
    
            if (!$commande) {
                // Générer un code unique pour la commande
                do {
                    $code_commandes = chr(rand(65, 90)) . rand(100, 999);
                } while (Commandes::where('code_commandes', $code_commandes)->exists());
    
                // Créer une nouvelle commande en cours si elle n'existe pas
                $commande = Commandes::create([
                    'users_id' => $userId,
                    'status' => 'encours',
                    'code_commandes' => $code_commandes,
                ]);
            }
    
            // Récupérer le produit
            $product = Product::find($request->product_id);

            // Règle métier : un panier ne peut contenir que des produits d'un
            // SEUL marchand. Barrière autoritaire (l'app a déjà un garde-fou,
            // mais on ne fait jamais confiance au client). On compare au
            // marchand des achats déjà présents dans la commande « encours ».
            $existingMarchandId = Achat::where('commande_id', $commande->id)
                ->join('products', 'achat.product_id', '=', 'products.id')
                ->value('products.marchand_id');

            if ($existingMarchandId !== null
                && (int) $existingMarchandId !== (int) $product->marchand_id) {
                return response()->json([
                    'status' => false,
                    'message' => "Votre panier contient déjà des produits d'un autre marchand. Videz-le pour commander chez celui-ci.",
                ], 409);
            }

            // Ajouter le produit au panier (table achat)
            $achat = Achat::create([
                'product_id' => $product->id,
                'qte_achat' => $request->qte_achat,
                'description_de_personnalisation' => $request->description_de_personnalisation,
                'commande_id' => $commande->id,
            ]);
    
            // Mettre à jour le prix total de la commande si nécessaire
            // $this->updateOrderTotal($commande->id);
    
            // Retourner le message avec l'ID de l'achat ajouté
            return response()->json([
                'status' => true,
                'message' => 'Produit ajouté au panier avec succès',
                'achat_id' => $achat->id,
                'data' => $achat
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    



    /**
     * Retirer un produit du panier.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromCart(Request $request)
{
    try {
  

        $userId = Auth::id();
        Log::info("Tentative de suppression de l'achat ID {$request->achat_id} par l'utilisateur ID {$userId}");

        // Trouver l'achat à supprimer
        $achat = Achat::find($request->achat_id);

        if (!$achat) {
            return response()->json([
                'status' => false,
                'message' => 'Achat introuvable',
            ], 404);
        }

        // Vérifier si l'achat appartient à une commande en cours pour l'utilisateur
        $commande = Commandes::where('id', $achat->commande_id)
            ->where('users_id', $userId)
            ->where('status', 'encours')
            ->first();

        if (!$commande) {
            Log::warning("Achat ID {$request->achat_id} non trouvé dans une commande en cours pour l'utilisateur ID {$userId}");
            return response()->json([
                'status' => false,
                'message' => 'Achat non trouvé dans une commande en cours pour cet utilisateur',
            ], 404);
        }

        // Supprimer l'achat
        $achat->delete();
        Log::info("Achat ID {$request->achat_id} supprimé avec succès du panier de l'utilisateur ID {$userId}");

        // Si la commande n'a plus aucun achat, la supprimer aussi
        if ($commande->achats()->count() === 0) {
            $commande->delete();
            Log::info("Commande ID {$commande->id} supprimée car vide.");
        }

        return response()->json([
            'status' => true,
            'message' => 'Produit supprimé du panier avec succès',
        ], 200);
    } catch (\Throwable $th) {
        Log::error("Erreur lors de la suppression de l'achat ID {$request->achat_id} : {$th->getMessage()}");
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}


/**
 * Supprimer les articles sélectionnés du panier.
 *
 * @param  Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function removeSelectedItems(Request $request)
{
    try {
        $userId = Auth::id();
        $selectedItemIds = $request->input('selectedItemIds'); // IDs des achats sélectionnés

        Log::info("Tentative de suppression des achats sélectionnés pour l'utilisateur ID {$userId}");

        // Vérifier si des achats sont fournis
        if (empty($selectedItemIds)) {
            return response()->json([
                'status' => false,
                'message' => 'Aucun article sélectionné pour la suppression',
            ], 400);
        }

        // Récupérer les IDs de commandes impactées AVANT suppression
        $commandeIds = Achat::whereIn('id', $selectedItemIds)
            ->whereHas('commande', function ($query) use ($userId) {
                $query->where('users_id', $userId)
                      ->where('status', 'encours');
            })
            ->pluck('commande_id')
            ->unique();

        // Supprimer les achats
        Achat::whereIn('id', $selectedItemIds)
            ->whereHas('commande', function ($query) use ($userId) {
                $query->where('users_id', $userId)
                      ->where('status', 'encours');
            })->delete();

        // Supprimer les commandes qui n'ont plus aucun achat
        \App\Models\Commandes::whereIn('id', $commandeIds)
            ->where('status', 'encours')
            ->doesntHave('achats')
            ->delete();

        Log::info("Les achats sélectionnés ont été supprimés avec succès pour l'utilisateur ID {$userId}");

        
        return response()->json([
            'status' => true,
            'message' => 'Les articles sélectionnés ont été supprimés avec succès du panier',
        ], 200);
    } catch (\Throwable $th) {
        Log::error("Erreur lors de la suppression des achats sélectionnés : {$th->getMessage()}");
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
}


// /**
//  * Récupérer les achats d'une commande en cours.
//  *
//  * @return \Illuminate\Http\JsonResponse
//  */
// public function recupererAchat()
// {
//     try {
//         $userId = Auth::id();

//         // Vérification si l'utilisateur est authentifié
//         if (!$userId) {
//             Log::warning('Utilisateur non authentifié');
//             return response()->json([
//                 'status' => false,
//                 'message' => "Utilisateur non authentifié"
//             ], 401);
//         }

//         // Recherche de la commande en cours
//         $commande = Commandes::where('users_id', $userId)
//                             ->where('status', 'encours')
//                             ->first();

//         if (!$commande) {
//             Log::info('Aucune commande en cours trouvée pour l\'utilisateur', ['user_id' => $userId]);
//             return response()->json([
//                 'status' => false,
//                 'message' => "L'utilisateur n'a pas d'achat en cours"
//             ], 404);
//         }

//         // Récupération des achats liés à la commande
//         $achats = Achat::where('commande_id', $commande->id)->get();

//         // Vérification si des achats existent
//         if ($achats->isEmpty()) {
//             Log::info('Aucun achat trouvé pour la commande', ['commande_id' => $commande->id]);
//             return response()->json([
//                 'status' => false,
//                 'message' => "Aucun achat trouvé pour cette commande"
//             ], 404);
//         }

//         // Initialisation et remplissage des détails des produits
//         $detailsProduits = $achats->map(function ($achat) {
//             $produit = $achat->product; // Relation produit présumée dans Achat

//             // Log pour vérifier les valeurs de l'achat et du produit
//             Log::info('Achat', ['achat' => $achat]);
//             Log::info('Produit', ['produit' => $produit]);

//             // Utilisation du prix promo si disponible, sinon du prix normal
//             $prix = $produit && $produit->prix_promo ? $produit->prix_promo : ($produit ? $produit->price : 0);

//             return [
//                 'nom' => $produit ? $produit->title : 'Inconnu',
//                 'prix' => $prix ? $prix : 100.2,
//                 'evaluation' => $produit ? $produit->evaluation : 2,
//                 'quantite' => $achat->qte_achat,
//                 'sous_total' => $prix * $achat->qte_achat,
//                 'personnalisable'=> "on",
//             ];
//         });

//         // Calcul du prix total en utilisant les prix promotionnels si disponibles
//         $prixTotal = $detailsProduits->sum('sous_total');

//         Log::info('Achats récupérés avec succès', ['user_id' => $userId, 'commande_id' => $commande->id, 'prix_total' => $prixTotal]);

//         // Construction de la réponse
//         return response()->json([
//             'status' => true,
//             'commande_id' => $commande->id,
//             'message' => 'Achat récupéré avec succès',
//             'data' => [
//                 'detailsProduits' => $detailsProduits,
//                 'prix_total' => $prixTotal
//             ]
//         ], 200);

//     } catch (\Throwable $th) {
//         Log::error('Erreur interne du serveur', ['message' => $th->getMessage(), 'trace' => $th->getTraceAsString()]);
//         return response()->json([
//             'status' => false,
//             'message' => "Erreur interne du serveur : " . $th->getMessage()
//         ], 500);
//     }
// }

public function updateQuantity(Request $request) {
    // Validation des données entrantes
    $request->validate([
        'achat_id' => 'required|exists:achat,id',
        'qte_achat' => 'required|integer|min:1',
    ]);

    // Récupérer l'ID de l'utilisateur authentifié
    $userId = auth()->user()->id;

    // Récupérer la commande de l'utilisateur en cours
    $commande = Commandes::where('users_id', $userId)
                ->where('status', 'encours')
                ->first();

    // Vérifier si une commande est disponible
    if (!$commande) {
        return response()->json([
            'status' => false,
            'message' => "Pas de commande disponible",
        ], 404);
    }

    // Récupérer l'achat correspondant à l'achat_id et commande_id
    $achat = Achat::where('id', $request->achat_id)
                  ->where('commande_id', $commande->id)
                  ->first();

    // Vérifier si l'achat existe
    if (!$achat) {
        return response()->json([
            'status' => false,
            'message' => "Achat non trouvé !",
        ], 404);
    }

    // Mettre à jour la quantité
    $achat->qte_achat = $request->qte_achat;
    $achat->save();

    // Répondre avec succès
    return response()->json([
        'status' => true,
        'message' => "Quantité mise à jour avec succès !",
    ]);
}


public function updateBeneficiere(Request $request)
{
    // Validation des données entrantes (delivery info optionnels selon le panier)
    $request->validate([
        'numero_beneficier' => 'required|string|min:8',
        'selectedOption' => 'required|string',
        'date_livraison' => 'nullable|date_format:Y-m-d',
        'heure_livraison' => 'nullable|date_format:H:i',
        'latitude_livraison' => 'nullable|numeric|between:-90,90',
        'longitude_livraison' => 'nullable|numeric|between:-180,180',
        'adresse_livraison' => 'nullable|string|max:500',
    ]);

    // Récupérer l'ID de l'utilisateur authentifié
    $userId = auth()->user()->id;

    // Récupérer la commande en cours de l'utilisateur
    $commande = Commandes::where('users_id', $userId)
                ->where('status', 'encours')
                ->first();

    // Vérifier si une commande est disponible
    if (!$commande) {
        return response()->json([
            'status' => false,
            'message' => "Pas de commande en cours disponible",
        ], 404);
    }

    // Mettre à jour les infos de livraison
    $commande->numero_beneficier = $request->numero_beneficier;
    $commande->selectedOption = $request->selectedOption;
    if ($request->filled('date_livraison')) {
        $commande->date_livraison = $request->date_livraison;
    }
    if ($request->filled('heure_livraison')) {
        $commande->heure_livraison = $request->heure_livraison;
    }
    if ($request->has('latitude_livraison')) {
        $commande->latitude_livraison = $request->latitude_livraison;
    }
    if ($request->has('longitude_livraison')) {
        $commande->longitude_livraison = $request->longitude_livraison;
    }
    if ($request->filled('adresse_livraison')) {
        $commande->adresse_livraison = $request->adresse_livraison;
    }
    $commande->save();

    // Répondre avec succès
    return response()->json([
        'status' => true,
        'message' => "Infos de livraison enregistrées avec succès !",
    ]);
}







/**
     * Récupérer les achats pour le panier de l'utilisateur connecté.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchAchats(Request $request)
    {
        // Vérifier que l'utilisateur est authentifié
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        $userId = Auth::id(); // Obtenir l'ID de l'utilisateur connecté

        $commande = Commandes::where('users_id', $userId)
                             ->where('status', 'encours')
                             ->first();

        // Pas de commande en cours => panier vide (et non une erreur 500).
        if (!$commande) {
            return response()->json([]);
        }

        // Récupérer tous les achats de l'utilisateur authentifié
        $achats = Achat::where('commande_id', $commande->id)
                       ->with('product.marchand')
                       ->get();

        // Auto-nettoyage des lignes orphelines : un produit (ou son marchand)
        // a pu être supprimé côté admin alors qu'il était encore dans un
        // panier. La ligne d'achat pointe alors dans le vide — invisible dans
        // l'app, mais elle fait planter l'affichage (product null) et bloque
        // l'ajout d'autres produits ("panier déjà rempli"). On la purge ici.
        $orphelins = $achats->filter(function ($achat) {
            return $achat->product === null || $achat->product->marchand === null;
        });

        if ($orphelins->isNotEmpty()) {
            Achat::whereIn('id', $orphelins->pluck('id'))->delete();
            $orphelinIds = $orphelins->pluck('id')->all();
            $achats = $achats->reject(function ($achat) use ($orphelinIds) {
                return in_array($achat->id, $orphelinIds, true);
            });

            // Panier désormais vide : on supprime la commande en cours pour
            // que l'app reparte d'un panier propre.
            if ($achats->isEmpty()) {
                $commande->delete();
                Log::info("Panier auto-nettoyé (orphelins) pour l'utilisateur ID {$userId} : commande {$commande->id} vidée et supprimée.");
                return response()->json([]);
            }

            Log::info("Panier auto-nettoyé (orphelins) pour l'utilisateur ID {$userId} : " . count($orphelinIds) . " ligne(s) supprimée(s).");
        }

        // Mapper les achats dans le format requis pour cartItems
        $cartItems = $achats->map(function ($achat) {
            $marchand = $achat->product->marchand;
            return [
                'product' => [
                    'id' => $achat->product->id,
                    'title' => $achat->product->title,
                    'price' => $achat->product->price,
                    'prix_promo' => $achat->product->prix_promo,
                    'evaluation' => $achat->product->evaluation,
                    'entreprise'=>$achat->product->entreprise,
                    'description'=>$achat->product->description,
                    'prixLivraison'=>$achat->product->prix_livraison,
                    'info'=>$achat->product->info,
                    'photo_main'=>$achat->product->photo_main,
                    'photo_secondary'=>$achat->product->photo_secondary,
                    'personnalisable'=>$achat->product->personnalisable,
                    'statut'=>$achat->product->statut,
                    'category'=>"none",
                    'subCategory'=>"none",
                    // Marchand (sans téléphone — $hidden côté modèle)
                    'marchand' => $marchand ? [
                        'id' => $marchand->id,
                        'nom' => $marchand->nom,
                        'adresse' => $marchand->adresse,
                        'ville' => $marchand->ville,
                        'latitude' => $marchand->latitude,
                        'longitude' => $marchand->longitude,
                        // Horaires du marchand : nécessaires au sélecteur de
                        // date/heure de livraison côté app (grisage des jours de
                        // fermeture + créneaux horaires). Sans ça, l'app retombe
                        // sur "tous les jours ouverts / 08:00–20:00".
                        'working_days' => $marchand->working_days,
                        'working_hours' => $marchand->working_hours,
                    ] : null,
                ],
                'quantity' => $achat->qte_achat, // Utiliser la quantité d'achat
                'achat_id' => $achat->id, // Identifiant de la ligne d'achat
                'description_de_personnalisation' => $achat->description_de_personnalisation,
                'isSelected' => false,
            ];
        });

        return response()->json($cartItems);
    }



public function createTransaction(Request $request)
{
    // Validation des données entrantes (le montant est recalculé côté serveur).
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'phone_number' => 'required|string',
        'operator' => 'required|string|in:mtn,moov,celtiis',
    ]);

    // Vérifier que l'utilisateur est authentifié
    if (!Auth::check()) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $userId = Auth::id(); // Obtenir l'ID de l'utilisateur connecté

    // Récupérer la commande en cours et calculer le montant CÔTÉ SERVEUR.
    $commande = Commandes::where('users_id', $userId)
        ->where('status', 'encours')
        ->first();

    if (!$commande) {
        return response()->json(['error' => "Aucune commande en cours à payer."], 404);
    }

    // Défense en profondeur : ne jamais lancer un paiement sur une commande
    // dont les informations de livraison n'ont pas été renseignées (l'app
    // l'impose déjà, mais un appel API direct pourrait les contourner).
    if (empty($commande->numero_beneficier) || empty($commande->selectedOption)) {
        return response()->json([
            'error' => "Les informations de livraison doivent être renseignées avant le paiement.",
        ], 422);
    }

    $amount = $this->computeOrderAmount($commande);

    if ($amount < 1) {
        return response()->json(['error' => "Le montant de la commande est invalide."], 422);
    }

    // Opérateur → mode FedaPay (Bénin). Le push USSD se fait via HTTP brut car
    // le SDK 0.4.2 ne connaît pas le mode « sbin » (Celtiis).
    $mode = $this->operatorMode($request->operator);
    $phone = $this->normalizeBeninPhoneForFedaPay($request->phone_number);

    // Log des variables reçues (jamais le montant client).
    Log::info('Données reçues pour la transaction:', [
        'user_id' => $userId,
        'commande_id' => $commande->id,
        'amount_serveur' => $amount,
        'operator' => $request->operator,
        'mode' => $mode,
    ]);

    // Configurer FedaPay depuis la config (.env).
    $this->configureFedaPay();

    try {
        // Créer la transaction
        $transaction = \FedaPay\Transaction::create([
            "description" => "Transaction for {$request->email}",
            "amount" => $amount,
            "currency" => ["iso" => "XOF"],
            // Ajout de l'ID utilisateur dans l'URL de callback (webhook serveur).
            "callback_url" => secure_url(route('payment.callback', ['user_id' => $userId])),
            "customer" => [
                "firstname" => $request->name,
                "lastname" => $request->name,
                "email" => $request->email,
                "phone_number" => [
                    "number" => $phone,
                    "country" => "BJ",
                ]
            ]
        ]);

        // Mémoriser l'id de transaction pour vérifier le paiement (polling/callback).
        $commande->fedapay_transaction_id = $transaction->id;
        $commande->save();

        // Générer le token (2e appel obligatoire avant le push USSD).
        $token = $transaction->generateToken()->token;

        // Push USSD direct sur le téléphone du client.
        $status = $this->pushUssd($mode, $token, $phone);

        Log::info('Push USSD envoyé', [
            'transaction_id' => $transaction->id,
            'operator' => $request->operator,
            'mode' => $mode,
            'status' => $status,
        ]);

        return response()->json([
            'status' => $status,
            'transaction_id' => $transaction->id,
            'code' => $commande->code_commandes,
        ]);
    } catch (\FedaPay\Error\ApiConnection $e) {
        Log::error('Erreur de connexion à l\'API FedaPay:', ['message' => $e->getMessage()]);
        return response()->json(['error' => 'Erreur de connexion à l\'API : ' . $e->getMessage()], 500);
    } catch (\FedaPay\Error\Authentication $e) {
        Log::error('Erreur d\'authentification FedaPay:', ['message' => $e->getMessage()]);
        return response()->json(['error' => 'Erreur d\'authentification : ' . $e->getMessage()], 401);
    } catch (\FedaPay\Error\InvalidRequest $e) {
        Log::error('Requête invalide FedaPay:', ['message' => $e->getMessage()]);
        return response()->json(['error' => 'Requête invalide : ' . $e->getMessage()], 400);
    } catch (\Exception $e) {
        Log::error('Erreur inconnue lors de la création de la transaction:', ['message' => $e->getMessage()]);
        return response()->json(['error' => 'Une erreur s\'est produite : ' . $e->getMessage()], 500);
    }
}




public function closePaiement(Request $request)
{
    // Récupérer l'ID de l'utilisateur à partir des paramètres d'URL
    $userId = $request->query('user_id');

    // Récupérer la commande de l'utilisateur
    $commande = Commandes::where('users_id', $userId)->where('status', 'encours')->first();

    // SÉCURITÉ : ne jamais faire confiance au 'status' passé dans l'URL.
    // On vérifie le paiement directement auprès de FedaPay via l'id mémorisé
    // lors de la création de la transaction.
    $paid = false;
    if ($commande && $commande->fedapay_transaction_id && $this->verifyFedaPayPayment($commande)) {
        $paid = true;
        $this->confirmOrder($commande, $userId);
    }

    // Rediriger en indiquant le VRAI résultat du paiement (jamais "success"
    // par défaut). L'app lit le paramètre `payment` pour afficher succès ou échec.
    return redirect()->route('paiement.success', [
        'user_id' => $userId,
        'payment' => $paid ? 'success' : 'echec',
    ]);
}

/**
 * Vérifie auprès de FedaPay qu'une transaction est réellement payée
 * et que son montant correspond au total attendu de la commande.
 */
private function verifyFedaPayPayment(Commandes $commande): bool
{
    try {
        $this->configureFedaPay();

        $transaction = \FedaPay\Transaction::retrieve($commande->fedapay_transaction_id);

        // Statuts considérés comme payés par FedaPay.
        $paidStatuses = ['approved', 'transferred', 'refunded'];
        if (!in_array($transaction->status, $paidStatuses, true)) {
            Log::warning('Paiement non confirmé par FedaPay', [
                'commande_id' => $commande->id,
                'transaction_id' => $commande->fedapay_transaction_id,
                'status' => $transaction->status ?? null,
            ]);
            return false;
        }

        // Le montant payé doit correspondre au montant attendu.
        $expected = $this->computeOrderAmount($commande);
        if ((int) $transaction->amount !== $expected) {
            Log::warning('Montant FedaPay incohérent', [
                'commande_id' => $commande->id,
                'attendu' => $expected,
                'recu' => $transaction->amount ?? null,
            ]);
            return false;
        }

        return true;
    } catch (\Throwable $e) {
        Log::error('Erreur vérification FedaPay', [
            'commande_id' => $commande->id,
            'error' => $e->getMessage(),
        ]);
        return false;
    }
}

    /**
     * Confirme une commande (statut + historique + FCM). Idempotent : n'agit
     * que si la commande est encore « encours ». Partagé par le callback
     * (flux hébergé) et le polling USSD (transactionStatus).
     */
    private function confirmOrder(Commandes $commande, $userId): void
    {
        if ($commande->status !== 'encours') {
            return;
        }

        $achats = Achat::where('commande_id', $commande->id)->get();
        $productIds = $achats->pluck('product_id')->unique()->toArray();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $sommeLivraison = $this->computeDeliveryFee($commande);
        $totalProduits = 0;
        $nomsProduits = [];

        foreach ($achats as $achat) {
            $product = $products->get($achat->product_id);

            if ($product) {
                $prixUnitaire = $product->prix_promo ? $product->prix_promo : $product->price;
                $achat->price = $prixUnitaire;
                $achat->save();

                $totalProduits += $prixUnitaire * $achat->qte_achat;
                $nomsProduits[] = $product->title;
            }
        }

        $commande->prix_total_commande = $totalProduits;
        $commande->prix_total_livraison = $sommeLivraison;
        $commande->status = 'confirmer';
        $commande->save();

        $descriptionProduits = implode(', ', $nomsProduits);

        Historique::create([
            'id_commande' => $commande->id,
            'users_id' => $userId,
            'description' => 'Commande supriz : ' . $descriptionProduits,
            'type' => 'simple',
            'prix' => $commande->prix_total_commande,
            'code_historiques' => $commande->code_commandes,
        ]);

        $user = User::find($userId);
        if ($user && $user->fcm_token) {
            $this->firebaseService->sendNotification(
                $user->fcm_token,
                'Commande confirmée',
                'Votre commande #' . $commande->code_commandes . ' a été confirmée. Total : ' . ($commande->prix_total_commande + $commande->prix_total_livraison) . ' FCFA.',
                [
                    'type' => 'order_confirmed',
                    'commande_id' => (string) $commande->id,
                    'code_commande' => (string) $commande->code_commandes,
                ]
            );
        }

        // Emails : confirmation au client + notification au marchand.
        $totalCommande = (int) round($commande->prix_total_commande + $commande->prix_total_livraison);

        if ($user && $user->email) {
            try {
                Mail::to($user->email)->send(new OrderConfirmation(
                    $user->nom,
                    $commande->code_commandes,
                    $descriptionProduits,
                    $totalCommande,
                ));
            } catch (\Throwable $e) {
                Log::error('Email confirmation commande non envoyé', [
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        try {
            Mail::to('contact@suprizs.com')->send(new MerchantOrderNotification(
                $commande->code_commandes,
                $descriptionProduits,
                $totalCommande,
                $user ? ($user->nom ?? 'Client') : 'Client',
                $user ? ($user->numero ?? '') : '',
            ));
        } catch (\Throwable $e) {
            Log::error('Email notification marchand non envoyé', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Statut du paiement en cours, pollé par l'app pendant le push USSD.
     * Sur « approved », on confirme la commande (idempotent).
     */
    public function transactionStatus(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = Auth::id();
        $commande = Commandes::where('users_id', $userId)
            ->where('status', 'encours')
            ->first();

        if (!$commande || !$commande->fedapay_transaction_id) {
            return response()->json(['status' => 'pending']);
        }

        $this->configureFedaPay();

        try {
            $transaction = \FedaPay\Transaction::retrieve($commande->fedapay_transaction_id);
            $status = $transaction->status ?? 'pending';
            $errorCode = $transaction->last_error_code ?? null;

            if ($this->isPaidStatus($status)) {
                $this->confirmOrder($commande, $userId);
                return response()->json([
                    'status' => 'approved',
                    'code' => $commande->code_commandes,
                ]);
            }

            if ($this->isFailedStatus($status)) {
                return response()->json([
                    'status' => 'declined',
                    'error_code' => $errorCode,
                    'message' => $this->fedaPayErrorMessage($errorCode, $status),
                ]);
            }

            return response()->json(['status' => 'pending']);
        } catch (\Throwable $e) {
            Log::error('Erreur statut transaction FedaPay', [
                'commande_id' => $commande->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['status' => 'pending']);
        }
    }

    /**
     * Message lisible du refus/échec FedaPay, à partir du code d'erreur renvoyé
     * par l'API (ex. INSUFFICIENT_FUND_ERROR → « solde insuffisant »).
     */
    private function fedaPayErrorMessage(?string $errorCode, string $status): string
    {
        return match ($errorCode) {
            'INSUFFICIENT_FUND_ERROR' => 'Solde insuffisant sur votre compte Mobile Money.',
            'TRANSACTION_CANCELED', 'CANCELED' => 'Paiement annulé.',
            'INVALID_PHONE_NUMBER', 'PHONE_NUMBER_NOT_FOUND' => 'Numéro Mobile Money invalide.',
            'INCORRECT_PIN' => 'Code PIN incorrect.',
            default => $status === 'canceled'
                ? 'Paiement non confirmé (expiré).'
                : 'Paiement refusé.',
        };
    }

    /**
     * Opérateur client → mode FedaPay (Bénin).
     */
    private function operatorMode(string $operator): string
    {
        return [
            'mtn' => 'mtn_open',
            'moov' => 'moov',
            'celtiis' => 'sbin',
        ][$operator] ?? 'moov';
    }

    /**
     * URL de base de l'API FedaPay selon l'environnement configuré.
     */
    private function fedapayBaseUrl(): string
    {
        return config('fedapay.environment', 'sandbox') === 'live'
            ? 'https://api.fedapay.com/v1'
            : 'https://sandbox-api.fedapay.com/v1';
    }

    /**
     * Numéro Bénin → 8 chiffres sans « 01 » (attendu par FedaPay pour le push).
     */
    private function normalizeBeninPhoneForFedaPay(string $phone): string
    {
        $d = preg_replace('/\D/', '', $phone);
        if (str_starts_with($d, '00229')) {
            $d = substr($d, 5);
        } elseif (str_starts_with($d, '229')) {
            $d = substr($d, 3);
        }
        if (strlen($d) === 8) {
            $d = '01' . $d;
        }
        return preg_replace('/^01/', '', $d);
    }

    /**
     * Push USSD direct (Mobile Money) sur le téléphone du client.
     * HTTP brut car le SDK 0.4.2 ne connaît pas le mode « sbin » (Celtiis).
     */
    private function pushUssd(string $mode, string $token, string $phone): string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('fedapay.secret_key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'supriz/1.0',
        ])->post($this->fedapayBaseUrl() . '/' . $mode, [
            'token' => $token,
            'phone_number' => [
                'number' => $phone,
                'country' => 'BJ',
            ],
        ]);

        if (!$response->successful()) {
            Log::error('Push USSD FedaPay en échec', [
                'mode' => $mode,
                'http_status' => $response->status(),
                'body' => $response->body(),
            ]);
            return 'failed';
        }

        $json = $response->json();
        $intent = $json['v1/payment_intent'] ?? $json['payment_intent'] ?? $json;
        return (string) ($intent['status'] ?? 'pending');
    }

    /**
     * Statuts FedaPay considérés comme payés.
     */
    private function isPaidStatus(string $status): bool
    {
        return in_array($status, [
            'approved', 'completed', 'transferred', 'refunded',
            'approved_partially_refunded', 'transferred_partially_refunded',
        ], true);
    }

    /**
     * Statuts FedaPay considérés comme refusés/échoués.
     */
    private function isFailedStatus(string $status): bool
    {
        return in_array($status, ['declined', 'failed', 'canceled', 'cancelled'], true);
    }

public function successPage(Request $request)
{
    // Récupérer l'ID de l'utilisateur
    $userId = $request->query('user_id');

    // 'success' par défaut pour rétro-compatibilité ; 'echec' si le paiement
    // n'a pas été confirmé par FedaPay.
    $paid = $request->query('payment', 'success') !== 'echec';

    // Retourner une vue reflétant le vrai résultat du paiement.
    return view('paiement.success', compact('userId', 'paid'));
}

    /**
     * Calculer les frais de livraison pour une commande.
     *
     * @param  Commandes  $commande
     * @return float
     */
    private function calculateDeliveryFee(Commandes $commande)
    {
        return $this->computeDeliveryFee($commande);
    }

    /**
     * Calculer le total des remises pour une commande.
     *
     * @param  Commandes  $commande
     * @return float
     */
    private function calculateTotalDiscount(Commandes $commande)
    {
        // Récupérer tous les produits concernés par la commande en une seule requête
        $achats = Achat::where('commande_id', $commande->id)->get();
        $productIds = $achats->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        // Calculer le total des remises : (prix_normal - prix_promo) * quantité
        $total_remise = $achats->reduce(function ($carry, $achat) use ($products) {
            $produit = $products->get($achat->product_id);
            if (!$produit || !$produit->prix_promo || $produit->prix_promo >= $produit->price) {
                return $carry;
            }
            $economie_unitaire = $produit->price - $produit->prix_promo;
            return $carry + $economie_unitaire * $achat->qte_achat;
        }, 0);

        return $total_remise;
    }




    
}