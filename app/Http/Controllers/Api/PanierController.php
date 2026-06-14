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

        // Trouver et supprimer les achats appartenant à l'utilisateur
        Achat::whereIn('id', $selectedItemIds)
            ->whereHas('commande', function ($query) use ($userId) {
                $query->where('users_id', $userId)
                      ->where('status', 'encours');
            })->delete();

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
    // Validation des données entrantes
    $request->validate([
        'numero_beneficier' => 'required|string|min:8',
        'selectedOption' => 'required|string',
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

    // Mettre à jour le numéro du bénéficiaire
    $commande->numero_beneficier = $request->numero_beneficier;
    $commande->selectedOption = $request->selectedOption;
    $commande->save();

    // Répondre avec succès
    return response()->json([
        'status' => true,
        'message' => "Numéro de bénéficiaire mis à jour avec succès !",
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
                    ] : null,
                ],
                'quantity' => $achat->qte_achat, // Utiliser la quantité d'achat
                'achat_id' => $achat->id, // Utiliser la quantité d'achat
                'isSelected' => false,
            ];
        });

        return response()->json($cartItems);
    }



public function createTransaction(Request $request)
{
    // Validation des données entrantes
    $request->validate([
        'amount' => 'required|numeric|min:1',
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

    // Log des variables reçues
    Log::info('Données reçues pour la transaction:', [
        'amount' => $request->amount,
        'name' => $request->name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'phone_number_country' => $request->phone_number_country,
    ]);

    // Définir la clé API et l'environnement
    FedaPay::setApiKey('sk_sandbox_sJ2DhG6GgFvt1FkG69EcCDia');
    // FedaPay::setApiKey('sk_live_uLv0-fHy7hbuMOjTSSfzBWaY');
    FedaPay::setEnvironment('sandbox'); // 'live' ou 'sandbox'

    try {
        // Créer la transaction
        $transaction = \FedaPay\Transaction::create([
            "description" => "Transaction for {$request->email}",
            "amount" => $request->amount,
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

        // Log de la transaction réussie
        Log::info('Transaction créée avec succès', [
            'transaction_id' => $transaction->id,
            'transaction_url' => $transaction->generateToken(),
        ]);

        $commande = Commandes::where('users_id', $userId)
        ->where('status', 'encours')
        ->first();

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
        
    // Logique pour vérifier et traiter la confirmation du paiement
    $data = $request->all();

    if ($data['status'] == 'approved') {
        // Récupérer la commande de l'utilisateur
        $commande = Commandes::where('users_id', $userId)->where('status', 'encours')->first();

        if ($commande) {
            // Récupérer les achats associés à la commande
            $achats = Achat::where('commande_id', $commande->id)->get();
            $sommeLivraison = 0;

            // Créer une liste pour stocker les noms des produits
            $nomsProduits = [];

            foreach ($achats as $achat) {
                // Récupérer le produit associé à l'achat
                $product = Product::find($achat->product_id); // Utiliser find pour récupérer le produit

                if ($product) { // Vérifier si le produit existe
                    // Mettre à jour le prix de l'achat
                    $achat->price = $product->prix_promo ? $product->prix_promo : $product->price;
                    $sommeLivraison += $product->prix_livraison; 

                    // Enregistrer les modifications sur l'achat
                    $achat->save();

                    // Ajouter le nom du produit à la liste
                    $nomsProduits[] = $product->title; // Assurez-vous que 'name' est la colonne correcte
                }
            }

            // Calculer les totaux de la commande
            $commande->prix_total_commande = $achats->sum('price');
            $commande->prix_total_livraison = $sommeLivraison;
            $commande->status = 'confirmer'; // Mettre à jour le statut de la commande
            $commande->save(); // Enregistrer les modifications sur la commande

            // Créer une chaîne contenant la liste des noms des produits

            $descriptionProduits = implode(', ', $nomsProduits);

            // Créer un enregistrement d'historique
            Historique::create([
                'id_commande' => $commande->id,
                'users_id' => $userId,
                'description' => 'Commande supriz : ' . $descriptionProduits,
                'type' => 'simple', // Ou 'supriz' selon le cas
                'prix' => $commande->prix_total_commande,
                'code_historiques' => $commande->code_commandes, // Générez un code unique
            ]);

            // Push FCM au client
            $user = User::find($userId);
            if ($user && $user->fcm_token) {
                $this->firebaseService->sendNotification(
                    $user->fcm_token,
                    'Commande confirmée',
                    'Votre commande #' . $commande->code_commandes . ' a été confirmée. Total : ' . $commande->prix_total_commande . ' FCFA.',
                    [
                        'type' => 'order_confirmed',
                        'commande_id' => (string) $commande->id,
                        'code_commande' => (string) $commande->code_commandes,
                    ]
                );
            }
        }
    }

    // Rediriger vers une page de succès
    return redirect()->route('paiement.success', ['user_id' => $userId]);
}

public function successPage(Request $request)
{
    // Récupérer l'ID de l'utilisateur
    $userId = $request->query('user_id');

    // Retourner une vue avec un message de succès
    return view('paiement.success', compact('userId'));
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

        // Calculer le total des remises
        $total_prix_promo = $achats->reduce(function ($carry, $achat) use ($products) {
            $produit = $products->get($achat->product_id);
            return $carry + ($produit ? $produit->prix_promo : 0);
        }, 0);

        return $total_prix_promo;
    }




    
}