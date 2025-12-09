<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href={{ asset('css/main.css') }}>
    <link rel="shortcut icon" href="{{ asset('/images/svg/llogosupriz2.svg') }}" type="image/x-icon">
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

        <div class="pt-6 pb-0 pl-6 pr-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex items-center justify-between mb-5">
                <img src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="">
                <h4 class="font-bold text-right text-white">Etat du stock</h4>
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

        <div class="pt-2 pb-0 pl-6 pr-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">

            <h2 class="font-bold text-center text-white">Tableau récapitulatif</h2>
            <div class="mt-2 mb-3 font-light text-center text-white ">Libellé du produit</div>
            <div class="flex items-center justify-between mb-4">
                <p class="font-light text-white">Vendu :</p>
                <h1 class="font-medium text-white">{{ $achats }}</h1>
            </div>
            <div class="flex items-center justify-between mb-4">
                <p class="font-light text-white">Nombre :</p>
                <h1 class="text-white">{{ $nbrproduits }}</h1>
            </div>

            <div class="flex items-center justify-between mb-4">
                <p class="font-light text-white">Montant encaissé :</p>
                <h1 class="text-white">{{ $montantEncaisse }}</h1>
            </div>

        </div>


        <div class="flex items-center justify-between mb-4">
            <p class="font-light text-black">Informations sur les produits</p>
            <a href="{{ route('admin.publierArticles') }}" class="flex p-2 text-white bg-green-900 rounded-20"><span><img
                        class="mr-2" src="{{ asset('/images/svg/add-square_svgrepo.com.svg') }}"
                        alt=""></span> <span>Ajouter
                    un produit</span></a>
        </div>


        <div
            class="p-3 overflow-x-auto bg-white border border-gray-100 shadow-md rounded-20 shadow-black/5 lg:col-span-2">
            <table class="w-full">

                <tbody>

                    <!-- Continue this pattern for the rest of the rows -->
                    @foreach ($produits as $item)
                        <tr>
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
                            <td class="flex px-4 py-2">
                                <a href="{{ route('admin.article.edit', $item->id) }}" class="mr-2 text-[13px] font-medium text-gray-400 cursor-pointer">
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
                            </td>
                        </tr>
                    @endforeach




                    <!-- Add the remaining rows as needed -->
                </tbody>
            </table>
        </div>


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

</body>

</html>
