<?php
namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Log;
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
            $livraison = $produit->prix_livraison ?? 0;
            return $carry + ($prixUnitaire * $achat->qte_achat) + $livraison;
        }, 0);

        return (int) round($total);
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

        $achat = Achat::where('commande_id',$commande->id);
        // Récupérer tous les achats de l'utilisateur authentifié
        $achats = $achat->with('product.marchand')
                       ->get();

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
        'phone_number_country' => 'required|string',
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

    // Log des variables reçues (jamais le montant client).
    Log::info('Données reçues pour la transaction:', [
        'user_id' => $userId,
        'commande_id' => $commande->id,
        'amount_serveur' => $amount,
        'phone_number_country' => $request->phone_number_country,
    ]);

    // Configurer FedaPay depuis la config (.env).
    $this->configureFedaPay();

    try {
        // Créer la transaction
        $transaction = \FedaPay\Transaction::create([
            "description" => "Transaction for {$request->email}",
            "amount" => $amount,
            "currency" => ["iso" => "XOF"],
            // Ajout de l'ID utilisateur dans l'URL de callback
            "callback_url" => secure_url(route('payment.callback', ['user_id' => auth()->user()->id])),
            "customer" => [
                "firstname" => $request->name,
                "lastname" => $request->name,
                "email" => $request->email,
                "phone_number" => [
                    "number" => $request->phone_number,
                    "country" => $request->phone_number_country,
                ]
            ]
        ]);

        // Mémoriser l'id de transaction pour vérifier le paiement au callback.
        $commande->fedapay_transaction_id = $transaction->id;
        $commande->save();

        // Log de la transaction réussie
        Log::info('Transaction créée avec succès', [
            'transaction_id' => $transaction->id,
        ]);

        return response()->json(['transaction_url' => $transaction->generateToken(),'code'=>$commande->code_commandes]);
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
        // Récupérer les achats associés à la commande
        $achats = Achat::where('commande_id', $commande->id)->get();
        $productIds = $achats->pluck('product_id')->unique()->toArray();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $sommeLivraison = 0;
        $totalProduits = 0;
        $nomsProduits = [];

        foreach ($achats as $achat) {
            $product = $products->get($achat->product_id);

            if ($product) {
                $prixUnitaire = $product->prix_promo ? $product->prix_promo : $product->price;
                $achat->price = $prixUnitaire;
                $achat->save();

                $totalProduits += $prixUnitaire * $achat->qte_achat;
                $sommeLivraison += $product->prix_livraison ?? 0;
                $nomsProduits[] = $product->title;
            }
        }

        // Calculer les totaux de la commande
        $commande->prix_total_commande = $totalProduits;
        $commande->prix_total_livraison = $sommeLivraison;
        $commande->status = 'confirmer'; // Mettre à jour le statut de la commande
        $commande->save();

        $descriptionProduits = implode(', ', $nomsProduits);

        // Créer un enregistrement d'historique
        Historique::create([
            'id_commande' => $commande->id,
            'users_id' => $userId,
            'description' => 'Commande supriz : ' . $descriptionProduits,
            'type' => 'simple',
            'prix' => $commande->prix_total_commande,
            'code_historiques' => $commande->code_commandes,
        ]);

        // Push FCM au client
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
        // Calculer le prix total de la livraison
        $total_prix_livraison = Achat::where('commande_id', $commande->id)
                        ->get()
                        ->reduce(function ($carry, $achat) {
                            $produit = Product::find($achat->product_id);
                            return $carry + ($produit ? $produit->prix_livraison : 0);
                        }, 0);

        return $total_prix_livraison;
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