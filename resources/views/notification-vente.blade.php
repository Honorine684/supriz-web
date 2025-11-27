<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href={{ asset('css/main.css') }}>
    <link rel="shortcut icon" href="{{ asset('/images/svg/llogosupriz2.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Supriz - Ventes</title>
</head>




<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">

    <!-- start: Sidebar -->
    @include('components.sidebar')
    <!-- end: Sidebar -->

    <!-- start: Main -->
    <main
        class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20 ">

        <div class="pt-6 pb-0 pl-6 pr-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex items-center justify-between mb-5">
                <img src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="">
                <h4 class="font-bold text-right text-white">Messages</h4>
                <div class="flex">
                    <img class="mr-3" src="{{ asset('/images/svg/menu-alt_svgrepo.com.svg') }}" alt="">

                    <div class="flex">
                        <input type="text" class="pr-10 rounded-20" placeholder="Recherche">
                        <img class="absolute cursor-pointer search_icon"
                            src="{{ asset('/images/svg/search-alt-1_svgrepo.com.svg') }}" alt="">
                    </div>
                </div>
            </div>

        </div>

        <div class="flex mt-3 "><a href="{{ route('admin.notificationVente') }}"><span
                    class="mr-4 cursor-pointer border-bottom-design text-color-design">Notification ventes</span></a><a
                href="{{ route('admin.notificationSupriz') }}"><span class="mr-4 cursor-pointer">Notification
                    Suppriz</span></a><a href="{{ route('admin.notificationCompte') }}"><span
                    class="cursor-pointer ">Notification Compte Client</span></a></div>
        <hr class="mb-10">

        @if ($errors->any())
            <div class="bg-red-500 text-center p-2 rounded text-white">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-500 text-center p-2 rounded text-white">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500 text-center p-2 rounded text-white">
                <p>{{ session('error') }}</p>
            </div>
        @endif


        <span class="p-5 mt-4 mb-4 relative rounded border w-full cursor-pointer block selected_bord">Operation en
            attente @if (count($commandes_encours) > 0)
                ({{ count($commandes_encours) }})
            @else
                {{ '(0)' }}
            @endif </span>

        <div class="selected_bord_body hidden">
            @if ($commandes_encours->isEmpty())
                <p>Aucune commande en cours...</p>
            @else
                @foreach ($commandes_encours as $commande)
                    <div class="p-3 mt-4 bg-gray-300 rounded">
                        <div class="flex items-center justify-between">
                            <span class="text-red-500 font-bold">Numero de Commande: P{{ $commande->id }}</span>
                            <span>{{ $commande->created_at->format('d/m/Y à H:i') }}</span>
                        </div>

                        <div class="flex items-center justify-between w-full mt-3 mb-3 ">
                            <span class="">Utilisateur :</span>
                            <span>
                                @if ($commande->user)
                                    {{ $commande->user->nom }} {{ $commande->user->prenom }}
                                @else
                                    Utilisateur inconnu
                                @endif
                            </span>
                        </div>

                        <div class="max-w-4xl mx-auto">
                            <table class="w-full text-left bg-white border border-gray-300 table-auto">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="w-1/4 px-4 py-2">Produit</th>
                                        <th class="w-1/6 px-4 py-2">Prix Unitaire</th>
                                        <th class="w-1/6 px-4 py-2">Quantité</th>
                                        <th class="w-1/3 px-4 py-2">Personnalisation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($commande->achats as $achat)
                                        <tr class="table-row-clickable cursor-copy" onclick="copyToClipboard(this)">
                                            <td class="px-4 py-2 border">
                                                @if ($achat->product)
                                                    {{ $achat->product->title }} ( {{ $achat->product->entreprise }} )
                                                @else
                                                    Produit inconnu
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border">
                                                {{ number_format($achat->product->prix_promo ? $achat->product->prix_promo + ($achat->product->prix_livraison) : $achat->product->price + ($achat->product->prix_livraison) , 0, ',', ' ') }}
                                                FCFA</td>
                                            <td class="px-4 py-2 border">{{ $achat->qte_achat }}</td>
                                            <td class="px-4 py-2 text-sm border">
                                                {{ $achat->description_de_personnalisation }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-200">
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 font-bold text-right">Prix total :</td>
                                        <td class="px-4 py-2 font-bold">
                                            {{ number_format($commande_price[$commande->id], 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="flex items-center justify-between w-full mt-4 mb-3">
                            <span class="">Numéro :</span>
                            <span>
                                @if ($commande->user)
                                    {{ $commande->user->numero }}
                                @else
                                    Non renseigné
                                @endif
                            </span>
                        </div>

                        <div class="flex items-center justify-between w-full mb-3 ">
                            <span class="">Destinataire :</span>
                            <span>{{ $commande->numero_beneficier }}</span> <!-- Destinataire de la commande -->
                        </div>

                        <div class="flex items-center justify-between w-full mb-3 ">
                            <span class="">Operation :</span>
                            <span class="text-yellow-500">{{ $commande->status }}</span>
                        </div>

                        <div class="flex items-center justify-between w-full mb-3 ">
                            <span class="">Livraison :</span>
                            <span class="text-yellow-500">En attente</span>
                        </div>

                        <input type="text" placeholder="Code : PXXXX" class="mb-2 rounded">
                        <button class="relative w-full p-1 text-white bg-green-600 rounded cursor-pointer rigth-0"
                            disabled>Validé</button>
                    </div>
                @endforeach
            @endif

        </div>





        <span class="p-5 mt-4 mb-4 relative rounded border w-full cursor-pointer block selected_bord">Livraison en
            attente @if (count($commandes_entente) > 0)
                ({{ count($commandes_entente) }})
            @else
                {{ '(0)' }}
            @endif </span>
        <div class="selected_bord_body hidden">
            @if ($commandes_entente->isEmpty())
                <p>Aucune commande en cours...</p>
            @else
                @foreach ($commandes_entente as $commande)
                    <div class="p-3 mt-4 bg-gray-300 rounded">
                        <div class="flex items-center justify-between">
                            <span class="text-red-500 font-bold">Numero de Commande: P{{ $commande->id }}</span>
                            <span>{{ $commande->created_at->format('d/m/Y à H:i') }}</span>
                        </div>

                        <div class="flex items-center justify-between w-full mt-3 mb-3 ">
                            <span class="">Utilisateur :</span>
                            <span>
                                @if ($commande->user)
                                    {{ $commande->user->nom }} {{ $commande->user->prenom }}
                                @else
                                    Utilisateur inconnu
                                @endif
                            </span>
                        </div>

                        <div class="max-w-4xl mx-auto">
                            <table class="w-full text-left bg-white border border-gray-300 table-auto">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="w-1/4 px-4 py-2">Produit</th>
                                        <th class="w-1/6 px-4 py-2">Prix Unitaire</th>
                                        <th class="w-1/6 px-4 py-2">Quantité</th>
                                        <th class="w-1/3 px-4 py-2">Personnalisation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($commande->achats as $achat)
                                        <tr class="table-row-clickable cursor-copy" onclick="copyToClipboard(this)">
                                            <td class="px-4 py-2 border">
                                                @if ($achat->product)
                                                    {{ $achat->product->title }} ( {{ $achat->product->entreprise }} )
                                                @else
                                                    Produit inconnu
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border">
                                                {{ number_format($achat->price + $achat->product->prix_livraison   , 0, ',', ' ') }}
                                                FCFA</td>
                                            <td class="px-4 py-2 border">{{ $achat->qte_achat }}</td>
                                            <td class="px-4 py-2 text-sm border">
                                                {{ $achat->description_de_personnalisation }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-200">
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 font-bold text-right">Prix total :</td>
                                        <td class="px-4 py-2 font-bold">
                                            {{ number_format($commande->prix_total_commande + $commande->prix_total_livraison  , 0, ',', ' ') }}
                                            FCFA</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="flex items-center justify-between w-full mt-4 mb-3">
                            <span class="">Numéro :</span>
                            <span>
                                @if ($commande->user)
                                    {{ $commande->user->numero }}
                                @else
                                    Non renseigné
                                @endif
                            </span>
                        </div>

                        <div class="flex items-center justify-between w-full mb-3 ">
                            <span class="">Destinataire :</span>
                            <span>{{ $commande->numero_beneficier }}</span> <!-- Destinataire de la commande -->
                        </div>

                        <div class="flex items-center justify-between w-full mb-3 ">
                            <span class="">Code :</span>
                            <span>{{ $commande->code_commandes }}</span> <!-- Destinataire de la commande -->
                        </div>

                        <div class="flex items-center justify-between w-full mb-3 ">
                            <span class="">Operation :</span>
                            <span class="text-yellow-500">{{ $commande->status }}</span>
                        </div>

                        <div class="flex items-center justify-between w-full mb-3 ">
                            <span class="">Livraison :</span>
                            <span class="text-yellow-500">En attente</span>
                        </div>
                        <form action="{{ route('admin.close.shop') }}" method="POST" class="mt-4">
                            @csrf
                            <div class="mb-4">
                                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Entrez le
                                    code
                                    de validation</label>
                                <input type="text" name="code" id="code" placeholder="Code : PXXXX"
                                    class="w-full px-4 py-2 mb-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    autocomplete="off">
                            </div>

                            <input type="hidden" name="command_id" value="{{ $commande->id }}">

                            <input type="submit" value="Validé"
                                class="w-full p-2 text-white bg-green-600 rounded cursor-pointer hover:bg-green-700 transition duration-200">
                        </form>

                    </div>
                @endforeach
            @endif


        </div>

        <span class="p-5 mt-4 mb-4 relative rounded border w-full cursor-pointer block selected_bord">Commande terminée
            @if (count($commandes_termine) > 0)
                ({{ count($commandes_termine) }})
            @else
                {{ '(0)' }}
            @endif
        </span>
        <div class="selected_bord_body hidden">
            @if ($commandes_termine->isEmpty())
                <p>Aucune commande en cours...</p>
            @else
                @foreach ($commandes_termine as $commande)
                    <div class="p-3 mt-4 bg-gray-300 rounded">
                        <div class="flex items-center justify-between">
                            <span class="text-red-500 font-bold">Numero de Commande: P{{ $commande->id }}</span>
                            <span>{{ $commande->created_at->format('d/m/Y à H:i') }}</span>
                        </div>

                        <div class="flex items-center justify-between w-full mt-3 mb-3 ">
                            <span class="">Utilisateur :</span>
                            <span>
                                @if ($commande->user)
                                    {{ $commande->user->nom }} {{ $commande->user->prenom }}
                                @else
                                    Utilisateur inconnu
                                @endif
                            </span>
                        </div>

                        <div class="max-w-4xl mx-auto">
                            <table class="w-full text-left bg-white border border-gray-300 table-auto">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="w-1/4 px-4 py-2">Produit</th>
                                        <th class="w-1/6 px-4 py-2">Prix Unitaire</th>
                                        <th class="w-1/6 px-4 py-2">Quantité</th>
                                        <th class="w-1/3 px-4 py-2">Personnalisation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($commande->achats as $achat)
                                        <tr class="table-row-clickable cursor-copy" onclick="copyToClipboard(this)">
                                            <td class="px-4 py-2 border">
                                                @if ($achat->product)
                                                    {{ $achat->product->title }} ( {{ $achat->product->entreprise }} )
                                                @else
                                                    Produit inconnu
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border">
                                                {{ number_format($achat->price + $achat->product->prix_livraison, 0, ',', ' ') }}
                                                FCFA</td>
                                            <td class="px-4 py-2 border">{{ $achat->qte_achat }}</td>
                                            <td class="px-4 py-2 text-sm border">
                                                {{ $achat->description_de_personnalisation }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-200">
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 font-bold text-right">Prix total :</td>
                                        <td class="px-4 py-2 font-bold">
                                            {{  number_format($commande->prix_total_commande + $commande->prix_total_livraison  , 0, ',', ' ') }}
                                            FCFA</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="flex items-center justify-between w-full mt-4 mb-3">
                            <span class="">Numéro :</span>
                            <span>
                                @if ($commande->user)
                                    {{ $commande->user->numero }}
                                @else
                                    Non renseigné
                                @endif
                            </span>
                        </div>

                        <div class="flex items-center justify-between w-full mb-3 ">
                            <span class="">Destinataire @if($commande->selectedOption == "Moi même") {{"(M)"}} @endif : </span>
                            <span>{{ $commande->numero_beneficier }}</span> <!-- Destinataire de la commande -->
                        </div>

                        <div class="flex items-center justify-between w-full mb-3 ">
                            <span class="">Operation :</span>
                            <span class="text-red-500">confirmer</span>
                        </div>

                        <div class="flex items-center justify-between w-full mb-3 ">
                            <span class="">Livraison :</span>
                            <span class="text-red-500">Livrer</span>
                        </div>


                    </div>
                @endforeach
            @endif


        </div>








        </div>
    </main>
    <!-- end: Main -->
    <script>
        function copyToClipboard(row) {
            // Titres des colonnes correspondant à chaque cellule (sauf "Prix Unitaire")
            const titles = ['Produit', 'Quantité', 'Personnalisation'];

            let textToCopy = '';
            const cells = row.querySelectorAll('td');

            // Boucler à travers les cellules et récupérer le texte, sauf la 2ème colonne (prix unitaire)
            cells.forEach((cell, index) => {
                // Ignorer la 2ème colonne (index 1)
                if (index !== 1) {
                    textToCopy += titles[index === 0 ? 0 : index - 1] + ': ' + cell.textContent.trim() +
                        '\n'; // Ajoute le titre et la valeur
                }
            });

            // Utiliser l'API Clipboard pour copier le texte
            navigator.clipboard.writeText(textToCopy.trim())
                .then(() => {
                    alert('Contenu copié dans le presse-papier !');
                })
                .catch(err => {
                    console.error('Erreur lors de la copie : ', err);
                });
        }
    </script>


    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="js/script.js"></script>
    <script src="{{ asset('js/showblock.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

</body>

</html>
