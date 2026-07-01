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
    <title>Supriz - Ventes</title>
</head>




<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">
    @include('partials.gtm-body')

    <!-- start: Sidebar -->
    @include('components.sidebar')
    <!-- end: Sidebar -->

    <!-- start: Main -->
    <main
        class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20 ">

        <div class="pt-4 md:pt-6 pb-0 px-4 md:px-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex items-center justify-between gap-3 flex-wrap mb-5">
                <img src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="" class="max-w-[120px] md:max-w-none h-auto">
                <h4 class="font-bold text-right text-white text-sm md:text-base">Messages</h4>
                <div class="flex items-center flex-wrap gap-2">
                    <img class="mr-3" src="{{ asset('/images/svg/menu-alt_svgrepo.com.svg') }}" alt="">

                    <div class="flex relative">
                        <input type="text" class="pr-10 rounded-20 w-full max-w-[200px]" placeholder="Recherche">
                        <img class="absolute cursor-pointer search_icon"
                            src="{{ asset('/images/svg/search-alt-1_svgrepo.com.svg') }}" alt="">
                    </div>
                </div>
            </div>

        </div>

        <div class="px-4 md:px-6">
        <div class="flex flex-wrap gap-y-2 mt-3">
            <a href="{{ route('admin.notificationVente') }}">
                <span class="mr-4 cursor-pointer border-bottom-design text-color-design text-sm md:text-base">Notification ventes</span>
            </a>
            <a href="{{ route('admin.notificationSupriz') }}">
                <span class="mr-4 cursor-pointer text-sm md:text-base">Notification Suppriz</span>
            </a>
            <a href="{{ route('admin.notificationCompte') }}">
                <span class="cursor-pointer text-sm md:text-base">Notification Compte Client</span>
            </a>
        </div>
        <hr class="mb-6 md:mb-10">

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
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm p-3 rounded-xl mb-4 flex items-center gap-2">
                <i class="ri-error-warning-line text-lg"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm p-3 rounded-xl mb-4 flex items-center gap-2">
                <i class="ri-checkbox-circle-line text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Onglets sobres --}}
        <div class="grid grid-cols-3 gap-2 mb-6 p-1 bg-gray-100 rounded-xl">
            <button type="button" data-section="section-encours"
                    class="section-tab flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-white/60 transition-all">
                <i class="ri-time-line"></i>
                <span>En cours</span>
                <span class="tab-count px-2 py-0.5 text-xs rounded-full bg-gray-200 text-gray-700">{{ count($commandes_encours) }}</span>
            </button>
            <button type="button" data-section="section-entente"
                    class="section-tab flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-white/60 transition-all">
                <i class="ri-truck-line"></i>
                <span>En attente</span>
                <span class="tab-count px-2 py-0.5 text-xs rounded-full bg-gray-200 text-gray-700">{{ count($commandes_entente) }}</span>
            </button>
            <button type="button" data-section="section-termine"
                    class="section-tab flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-white/60 transition-all">
                <i class="ri-checkbox-circle-line"></i>
                <span>Livrées</span>
                <span class="tab-count px-2 py-0.5 text-xs rounded-full bg-gray-200 text-gray-700">{{ count($commandes_termine) }}</span>
            </button>
        </div>

        <style>
            .section-tab.active {
                background: #ffffff;
                color: #005792;
                box-shadow: 0 1px 3px rgba(0,0,0,.08);
            }
            .section-tab.active .tab-count {
                background: rgba(0,87,146,.1);
                color: #005792;
            }
            .commande-section { display: none; }
            .commande-section.active {
                display: block;
                animation: fadeInUp .25s ease-out;
            }
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(6px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>

        {{-- Section: En cours --}}
        <div id="section-encours" class="commande-section active">
            @if ($commandes_encours->isEmpty())
                <div class="bg-white border border-dashed border-gray-300 rounded-xl p-8 text-center">
                    <i class="ri-inbox-line text-4xl text-gray-300 block mb-2"></i>
                    <p class="text-gray-500 text-sm">Aucune opération en attente</p>
                </div>
            @else
                @foreach ($commandes_encours as $commande)
                    @include('partials.commande-card', [
                        'commande' => $commande,
                        'totalCalcul' => $commande_price[$commande->id] ?? null,
                    ])
                @endforeach
            @endif
        </div>

        {{-- Section: Livraison en attente --}}
        <div id="section-entente" class="commande-section">
            @if ($commandes_entente->isEmpty())
                <div class="bg-white border border-dashed border-gray-300 rounded-xl p-8 text-center">
                    <i class="ri-truck-line text-4xl text-gray-300 block mb-2"></i>
                    <p class="text-gray-500 text-sm">Aucune commande en attente de livraison</p>
                </div>
            @else
                @foreach ($commandes_entente as $commande)
                    @include('partials.commande-card', ['commande' => $commande, 'totalCalcul' => null])
                @endforeach
            @endif
        </div>

        {{-- Section: Terminées --}}
        <div id="section-termine" class="commande-section">
            @if ($commandes_termine->isEmpty())
                <div class="bg-white border border-dashed border-gray-300 rounded-xl p-8 text-center">
                    <i class="ri-inbox-archive-line text-4xl text-gray-300 block mb-2"></i>
                    <p class="text-gray-500 text-sm">Aucune commande terminée</p>
                </div>
            @else
                @foreach ($commandes_termine as $commande)
                    @include('partials.commande-card', ['commande' => $commande, 'totalCalcul' => null])
                @endforeach
            @endif
        </div>

        <script>
            (function () {
                const tabs = document.querySelectorAll('.section-tab');
                const sections = document.querySelectorAll('.commande-section');
                // init active tab
                const initId = document.querySelector('.commande-section.active')?.id;
                tabs.forEach(t => {
                    if (t.dataset.section === initId) t.classList.add('active');
                    t.addEventListener('click', () => {
                        const id = t.dataset.section;
                        tabs.forEach(x => x.classList.remove('active'));
                        sections.forEach(s => s.classList.remove('active'));
                        t.classList.add('active');
                        document.getElementById(id)?.classList.add('active');
                    });
                });
            })();
        </script>


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
