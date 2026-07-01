<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href={{ asset('css/main.css') }}>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Supriz - Rapports </title>
</head>


<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">
    @include('partials.gtm-body')

    <!-- start: Sidebar -->
    @include('components.sidebar')
    <!-- end: Sidebar -->

    <!-- start: Main -->
    <main
        class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20 p-3 md:p-4 overflow-x-hidden">

        <div class="pt-4 md:pt-6 pb-0 px-3 md:px-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-5 gap-3">
                <img class="max-w-full h-auto" src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="">
                <h4 class="font-bold text-center md:text-right text-white text-base md:text-lg">Messages</h4>
                <div class="flex items-center flex-wrap gap-2">
                    <img class="mr-3" src="{{ asset('/images/svg/menu-alt_svgrepo.com.svg') }}" alt="">

                    <div class="flex relative w-full md:w-auto">
                        <input type="date" id="searchInput" class="pr-10 rounded-20 w-full md:w-auto" placeholder="Recherche">
                        <img class="absolute cursor-pointer search_icon" src="{{ asset('/images/svg/search-alt-1_svgrepo.com.svg') }}" alt="">
                    </div>

                </div>
            </div>

        </div>

        @foreach ($bilans as $bilan)
        <div class="bg-gray-100 p-4 md:p-6 rounded-20 m-2 md:m-3 bilan-item overflow-x-auto" data-search="{{ $bilan->created_at->format('Y-m-d') }}">
            <h2 class="text-center text-sm md:text-base">Le {{ $bilan->created_at->format('d-m-y') }} à
                    {{ $bilan->created_at->format('h:i') }}</h2>
                <h1 class="font-bold text-center mb-6 md:mb-8 text-lg md:text-xl">Produits vendus</h1>

                <div class="flex items-center justify-between">
                    <span>Produit vendu :</span>
                    <h1 class="font-bold">{{ $bilan->nbr_produit_vendu }}</h1>
                </div>

                <div class="flex items-center justify-between">
                    <span>Somme totale :</span>
                    <h1 class="font-bold">{{ $bilan->somme_total }}</h1>
                </div>

                <div class="flex items-center justify-between">
                    <span>Bénéfice :</span>
                    <h1 class="font-bold">{{ $bilan->benefices }}</h1>
                </div>

                <div class="flex items-center justify-between">
                    <span>Nombre de livraison :</span>
                    <h1 class="font-bold">{{ $bilan->nbr_livraison }}</h1>
                </div>

                <div class="flex items-center justify-between">
                    <span>Prix total de livraison :</span>
                    <h1 class="font-bold">{{ $bilan->prix_total_livraison }}</h1>
                </div>

                <h1 class="font-bold text-center mb-6 md:mb-8 text-lg md:text-xl mt-4">Message Supriz</h1>

                <!-- Assuming 'messagesSupriz' is a relationship on the Bilan model -->
                <div class="flex items-center justify-between">
                    <span>Messages :</span>
                    <h1 class="font-bold">{{ $bilan->messagesSupriz->count() }}</h1>
                </div>

                @foreach ($bilan->messagesSupriz as $item)
                    <div class="flex items-center justify-between">
                        <span>{{ $item->nom }} :</span>
                        <h1 class="font-bold">{{ $item->nbr_total }}</h1>
                    </div>
                @endforeach

                <div class="flex items-center justify-between">
                    <span>Montant encaissé :</span>
                    <h1 class="font-bold">{{ $bilan->montant_total_supriz }}</h1>
                </div>

                <h1 class="font-bold text-center mb-6 md:mb-8 text-lg md:text-xl mt-4">Détails de l'entreprise</h1>

                <div class="mx-auto overflow-x-auto">
                    <table class="w-full text-left bg-white border border-black table-auto">
                        <tbody>
                            @foreach ($bilan->entreprises as $entreprise)
                                <!-- Assuming 'entreprises' is a relationship -->
                                <tr class="flex items-center justify-between">
                                    <td class="px-2 md:px-4 py-2 text-sm md:text-base">Nom de l’entreprise :</td>
                                    <td class="px-2 md:px-4 py-2 font-bold text-sm md:text-base">{{ $entreprise->nom }}</td>
                                </tr>
                                <tr class="flex items-center justify-between">
                                    <td class="px-2 md:px-4 py-2 text-sm md:text-base">Produit vendu :</td>
                                    <td class="px-2 md:px-4 py-2 font-bold text-sm md:text-base">{{ $entreprise->nbr_produits_vendu }}</td>
                                </tr>
                                <tr class="flex items-center justify-between">
                                    <td class="px-2 md:px-4 py-2 text-sm md:text-base">Montant encaissé :</td>
                                    <td class="px-2 md:px-4 py-2 font-bold text-sm md:text-base">{{ $entreprise->montant_encaisse }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach









    </main>
    <!-- end: Main -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const bilanItems = document.querySelectorAll('.bilan-item');
    
            searchInput.addEventListener('input', function() {
                let filter = searchInput.value;  // La valeur est au format 'YYYY-MM-DD'
                
                bilanItems.forEach(function(item) {
                    let dateText = item.getAttribute('data-search');  // Récupère la date au format 'YYYY-MM-DD'
                    
                    // Si la valeur de recherche correspond exactement à la date du bilan, afficher l'élément
                    if (dateText === filter) {
                        item.style.display = '';  // Affiche l'élément
                    } else {
                        item.style.display = 'none';  // Cache l'élément si non correspondance
                    }
                });
            });
        });
    </script>
    
    
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="js/chart.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>

</body>

</html>
