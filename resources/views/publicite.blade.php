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
    <title>Supriz - Publicité</title>
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

        <div class="flex mt-3"><a href="{{ route('notification') }}"><span class="mr-4 cursor-pointer">Rédiger une
                    notification</span></a><a href="{{ route('publicite') }}"><span
                    class="cursor-pointer text-color-design border-bottom-design">Faire une publicité</span></a></div>
        <hr class="bottom-10">
        <div class="p-2">
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

            <form action="{{ route('publicite.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h1 class="mt-4 font-bold text-color-design f-20">Faire une publicité</h1>
                <div class="h-input-file relative">
                    <img src="{{ asset('/images/svg/streamline_delete-1-solid.svg') }}"
                        class="absolute costum_close removeimage hidden right-0 float-right cursor-pointer text-end "
                        alt="">
                    <img src="{{ asset('images/svg/group_10.svg') }}" id="imagePreview"
                        class="mt-3 cursor-pointer h-input-file" alt="">
                    <input type="file" name="image" id="imageInput" accept="image/*" name=""
                        class="relative opacity-0 cursor-pointer h-input-file -t-15">
                </div>
                <div class="flex w-full mt-2">
                    <div class="grid index-30 w-1/2">
                        <span class="text-gray-400">Catégories</span>
                        <div class="flex items-center justify-between w-10 radio-column">
                            <input type="radio" name="categorie_dhomme" value="h"
                                onchange="loadSousCategories('h')" class="mr-2">
                            <span>Homme</span>
                        </div>

                        <div class="flex items-center justify-between w-10 radio-column">
                            <input type="radio" name="categorie_dhomme" value="f"
                                onchange="loadSousCategories('f')" class="mr-2">
                            <span>Femme</span>
                        </div>

                        <div class="flex items-center justify-between w-10 radio-column">
                            <input type="radio" name="categorie_dhomme" value="e"
                                onchange="loadSousCategories('e')" class="mr-2">
                            <span>Enfant</span>
                        </div>
                    </div>

                    <div class="grid w-1/2 ml-10">
                        <span class="mb-2 text-gray-400">Sous-Catégories</span>
                        <select name="sousCategorieId" id="sous-categorie" class="p-1 rounded">
                            <option value="0">Sous-Catégories</option>
                        </select>
                    </div>
                </div>

                <input type="submit" class="w-full p-2 mt-2 text-white rounded cursor-pointer btn-design">
            </form>

            <p class="mt-3 mb-2">Les publicités</p>

            <div class="flex w-full overflow-x-auto">
                @foreach ($publicite as $item)
                    <div class="grid p-3 mr-3 rounded-20 bg-gray-primary2">
                        <div class="relative img-pub image-publicite">
                            <form action="{{ route('publicite.destroy', $item->id) }}" method="POST" class="absolute right-0 float-right cursor-pointer text-end ">
                                @csrf
                                @method('DELETE') <!-- Cette méthode est utilisée pour les requêtes de suppression -->
                                <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette Publicite ?')"><img src="{{ asset('/images/svg/streamline_delete-1-solid.svg') }}"
                                     alt=""></button>
                            </form>
                            
                            
                            <img src="{{ asset('storage/' . $item->pathImage) }}" class="image-publicite"
                                alt="">
                        </div>

                        <div class="flex items-center justify-between mt-2 info">
                            <p>{{$item->created_at->format("d/m/y")}}</p>
                            <p>{{$item->created_at->format("h:i")}}</p>
                        </div>
                    </div> 
                @endforeach
                


            </div>
        </div>





        </div>
    </main>
    <!-- end: Main -->

    <script>
        var defaultImageUrl = "{{ asset('images/svg/group_10.svg') }}";
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        // Fonction pour charger les sous-catégories via AJAX
        function loadSousCategories(categorie) {
            $.ajax({
                url: '/get-sous-categories', // L'URL de ta route Laravel
                type: 'POST',
                data: {
                    categorie: categorie,
                    _token: '{{ csrf_token() }}' // N'oublie pas d'ajouter le token CSRF pour la sécurité
                },
                success: function(response) {
                    const sousCategorieSelect = document.getElementById('sous-categorie');

                    // Vider les options actuelles
                    sousCategorieSelect.innerHTML = '<option value="none">Sous-Catégories</option>';

                    // Ajouter les nouvelles sous-catégories retournées par le serveur
                    response.forEach(function(sousCategorie) {
                        const option = document.createElement('option');
                        option.value = sousCategorie.id; // Utiliser l'ID comme valeur
                        option.textContent = sousCategorie.name; // Utiliser le nom pour l'affichage
                        sousCategorieSelect.appendChild(option);
                    });
                },
                error: function(xhr) {
                    console.error("Erreur lors du chargement des sous-catégories.");
                }
            });
        }
    </script>

    <script src="js/imagefile.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="js/chart.js"></script>
    <script src="{{ asset('js/imagefile.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

</body>

</html>
