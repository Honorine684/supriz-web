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
    <title>Supriz - Stocks</title>
</head>


<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">
    @include('partials.gtm-body')

    <!-- start: Sidebar -->
    @include('components.sidebar')
    <!-- end: Sidebar -->

    <!-- start: Main -->
    <main class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20 ">

        <div class="pt-4 md:pt-6 pb-0 px-4 md:px-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex items-center justify-between gap-3 flex-wrap mb-5">
                <img src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="" class="max-w-[120px] md:max-w-none h-auto">
                <h4 class="font-bold text-right text-white text-sm md:text-base">Etat du stock</h4>
                <div class="flex items-center flex-wrap gap-2">
                    <img class="mr-3" src="{{ asset('/images/svg/menu-alt_svgrepo.com.svg') }}" alt="">

                    {{-- Entrée = recherche serveur sur tout le catalogue (?q=) ;
                         la frappe filtre aussi la page affichée (partials.recherche-liste). --}}
                    <form method="GET" action="{{ route('admin.stocks') }}" class="flex relative">
                        <input type="text" name="q" value="{{ request('q') }}" id="recherche-produit" class="pr-10 rounded-20 w-full max-w-[200px]" placeholder="Recherche">
                        <img class="absolute cursor-pointer search_icon"
                            src="{{ asset('/images/svg/search-alt-1_svgrepo.com.svg') }}" alt="">
                    </form>
                </div>
            </div>

        </div>

        <div class="px-4 md:px-6">
        <div class="pt-2 pb-4 px-4 md:px-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">

            <h2 class="font-bold text-center text-white text-lg md:text-xl">Tableau récapitulatif</h2>
            <div class="mt-2 mb-3 font-light text-center text-white ">Libellé du produit</div>
            <div class="flex items-center justify-between gap-2 flex-wrap mb-4">
                <p class="font-light text-white">Vendu :</p>
                <h1 class="font-medium text-white">{{ $achats }}</h1>
            </div>
            <div class="flex items-center justify-between gap-2 flex-wrap mb-4">
                <p class="font-light text-white">Nombre :</p>
                <h1 class="text-white">{{ $nbrproduits }}</h1>
            </div>

            <div class="flex items-center justify-between gap-2 flex-wrap mb-4">
                <p class="font-light text-white">Montant encaissé :</p>
                <h1 class="text-white">{{ $montantEncaisse }}</h1>
            </div>

        </div>


        <div class="flex items-center justify-between gap-2 flex-wrap mb-4">
            <p class="font-light text-black">Informations sur les produits</p>
            <a href="{{ route('admin.publierArticles') }}" class="flex items-center p-2 text-white bg-green-900 rounded-20 text-sm"><span><img
                        class="mr-2" src="{{ asset('/images/svg/add-square_svgrepo.com.svg') }}"
                        alt=""></span> <span>Ajouter
                    un produit</span></a>
        </div>


        <div
            class="p-3 overflow-x-auto bg-white border border-gray-100 shadow-md rounded-20 shadow-black/5 lg:col-span-2">
            <table class="w-full min-w-[720px]" id="table-produits">

                <tbody>

                    <!-- Continue this pattern for the rest of the rows -->
                    @foreach ($produits as $item)
                        <tr>
                            <td class="px-4 py-2 border-b border-b-gray-50">
                                <img class="w-12 h-12 rounded object-cover"
                                     src="{{ asset('storage/' . $item->photo_main) }}" alt="">
                            </td>
                            <td class="px-4 py-2 border-b w-60 border-b-gray-50">
                                <span class="text-[13px] font-medium text-gray-400">
                                    {{ $item->title }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border-b w-60 border-b-gray-50">
                                <span class="text-[13px] font-medium text-gray-400">
                                    @if ($item->prix_promo)
                                        {{ $item->prix_promo }} FCFA
                                    @else
                                        {{ $item->price }} FCFA
                                    @endif
                                </span>
                            </td>
                            <td class="w-40 border-b border-b-gray-50">
                                <select name="stock_status_{{ $item->id }}"
                                    class="border-none rounded mr-10 statut-change"
                                    data-produit-id="{{ $item->id }}">
                                    <option value="in_stock" class="text-green-500"
                                        {{ $item->statut == 'in_stock' ? 'selected' : '' }}>En stock</option>
                                    <option value="unavailable" class="text-red-500"
                                        {{ $item->statut == 'unavailable' ? 'selected' : '' }}>Indisponible</option>
                                </select>
                            </td>
                            <td class="w-40 px-4 py-2 border-b border-b-gray-50">
                                <span class="text-[13px] font-medium text-gray-400">{{ $item->entreprise }}</span>
                            </td>
                            <td class="px-4 py-2 border-b border-b-gray-50">
                                <div class="flex items-center gap-2">
                                <a href="{{ route('admin.article.edit', $item->id) }}" class="text-[13px] font-medium text-gray-400 cursor-pointer">
                                    <img class="relative top-1" src="{{ asset('/images/svg/edit-3_svgrepo.com.svg') }}" alt="Editer">
                                </a>
                                <form action="{{ route('admin.produit.destroy', $item->id) }}" method="POST" class="text-[13px] font-medium text-gray-400 cursor-pointer">
                                    @csrf
                                    @method('DELETE')
                                    <!-- Cette méthode est utilisée pour les requêtes de suppression -->
                                    <button type="submit"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce Produit ?')"> <span
                                            ><img
                                                class="relative top-1"
                                                src="{{ asset('/images/svg/delete-left_svgrepo.com.svg') }}"
                                                alt=""></span></button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach




                    <!-- Add the remaining rows as needed -->
                </tbody>
            </table>
        </div>

        {{ $produits->onEachSide(1)->links('partials.pagination') }}

        </div>
    </main>
    <!-- end: Main -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.statut-change').change(function() {
                var produitId = $(this).data('produit-id');
                var statut = $(this).val();

                $.ajax({
                    url: "{{ route('admin.produit.changerStatut') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        produit_id: produitId,
                        statut: statut
                    },
                    success: function(response) {
                        alert(response.message);
                    },
                    error: function(xhr) {
                        alert('Erreur lors de la mise à jour du statut' + xhr.responseText);
                    }
                });
            });
        });
    </script>

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    @include('partials.recherche-liste')
</body>

</html>
