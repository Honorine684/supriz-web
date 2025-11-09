<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href={{ asset('css/main.css') }}>
    <link rel="shortcut icon" href="{{ asset('/images/svg/llogosupriz2.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Supriz - Catégorie</title>
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
                <h4 class="font-bold text-right text-white">Catégoeries</h4>
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
        @if (session('success'))
            <div class="bg-green-500 text-center p-2 rounded text-white">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500 text-center p-2 rounded text-white">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-500 text-center p-2 rounded text-white">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif


        <form class="mt-5" action="{{ isset($sousCategorie) ? route('categorie.update', $sousCategorie->id) : route('add.categorie') }}" method="POST">
            @csrf
            @if(isset($sousCategorie))
                @method('PUT')
            @endif
        
            <h2 class="font-bold mb-4">{{ isset($sousCategorie) ? 'Modifier' : 'Ajouter' }} une Sous-Catégorie</h2>
        
            <div class="mb-4">
                <label for="categorie" class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                <select name="categorie"  id="categorie" class="block w-full p-2 border rounded">
                    <option value="" disabled {{ !isset($sousCategorie) ? 'selected' : '' }}>Choisir une catégorie</option>
                    <option value="h" {{ (isset($sousCategorie) && $sousCategorie->categorie->name_id_categories == 'h') ? 'selected' : '' }}>Homme</option>
                    <option value="f" {{ (isset($sousCategorie) && $sousCategorie->categorie->name_id_categories == 'f') ? 'selected' : '' }}>Femme</option>
                    <option value="e" {{ (isset($sousCategorie) && $sousCategorie->categorie->name_id_categories == 'e') ? 'selected' : '' }}>Enfant</option>
                    <option value="s" {{ (isset($sousCategorie) && $sousCategorie->categorie->name_id_categories == 's') ? 'selected' : '' }}>Supriz</option>
                </select>
                @error('categorie')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        
            <div class="mb-4">
                <label for="sousCategorie" class="block text-sm font-medium text-gray-700 mb-2">Sous-Catégorie</label>
                <input type="text" name="sousCategorie" id="sousCategorie" class="block w-full p-2 border rounded"
                       placeholder="Nom de la sous-catégorie"
                       value="{{ isset($sousCategorie) ? $sousCategorie->name_sous_categories : '' }}">
                @error('sousCategorie')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        
            <div class="mb-4">
                <label for="prix" class="block text-sm font-medium text-gray-700 mb-2">Prix (Optionnel)</label>
                <input type="number" name="prix" id="prix" class="block w-full p-2 border rounded"
                       placeholder="Prix de la sous-catégorie"
                       value="{{ isset($sousCategorie) ? $sousCategorie->prix : '' }}">
                @error('prix')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        
            <div>
                <input type="submit" name="submit"
                       class="w-full relative right-0 rounded p-2 text-white cursor-pointer btn-design mt-1"
                       value="{{ isset($sousCategorie) ? 'Modifier' : 'Enregistrer' }}">
            </div>
        </form>


        <!-- Section pour gérer les images des catégories principales -->
        <div class="mt-8 mb-6">
            <h2 class="font-bold mb-4">Gérer les Images des Catégories Principales</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($categories as $categorie)
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow">
                    <h3 class="font-semibold text-lg mb-3">{{ $categorie->libelle_homme_categories }}</h3>

                    <!-- Image actuelle -->
                    @if($categorie->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $categorie->image) }}" alt="{{ $categorie->libelle_homme_categories }}" class="w-full h-32 object-cover rounded">
                    </div>
                    @else
                    <div class="mb-3 bg-gray-100 h-32 flex items-center justify-center rounded">
                        <span class="text-gray-400">Aucune image</span>
                    </div>
                    @endif

                    <!-- Formulaire d'upload -->
                    <form action="{{ route('categorie.updateImage', $categorie->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                            <input type="file" name="image" accept="image/*,.gif" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                            {{ $categorie->image ? 'Modifier l\'image' : 'Ajouter une image' }}
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>


        <p class="mt-3 mb-2">Liste des catégories</p>

        <div
            class="p-3 overflow-x-auto bg-white border border-gray-100 shadow-md rounded-20 shadow-black/5 lg:col-span-2">
            <table class="w-full">

                <tbody>
                    @if ($sousCategories)

                        @foreach ($sousCategories as $sousCategorie)
                            <!-- Continue this pattern for the rest of the rows -->
                            <tr>
                                <td class="px-4 py-2 border-b w-60 border-b-gray-50">
                                    <span class="text-[13px] font-medium text-gray-400">
                                        {{ $sousCategorie->name_sous_categories }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 border-b w-60 border-b-gray-50">
                                    <span class="text-[13px] font-medium text-gray-400">{{ $sousCategorie->categorie->libelle_homme_categories }}</span>
                                </td>

                                <td class="px-4 py-2 border-b w-60 border-b-gray-50">
                                    <span class="text-[13px] font-medium text-gray-400">{{ $sousCategorie->prix ?? 'N/A' }}</span>
                                </td>

                                <td class="flex px-4 py-2 ">
                                    <a href="{{ route('categorie.edit', $sousCategorie->id) }}" class="mr-2 text-[13px] font-medium text-gray-400 cursor-pointer">
                                        <img class="relative top-1" src="{{ asset('/images/svg/edit-3_svgrepo.com.svg') }}" alt="Editer">
                                    </a>
                                            <form action="{{ route('categorie.destroy', $sousCategorie->id) }}" method="POST" class="text-[13px] font-medium text-gray-400 cursor-pointer">
                                                @csrf
                                                @method('DELETE')
                                                <!-- Cette méthode est utilisée pour les requêtes de suppression -->
                                                <button type="submit"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette categorie ?')"> <span
                                                        ><img
                                                            class="relative top-1"
                                                            src="{{ asset('/images/svg/delete-left_svgrepo.com.svg') }}"
                                                            alt=""></span></button>
                                            </form>
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <p class="text-center">Aucune données disponible</p>
                    @endif

                    
                    <!-- Add the remaining rows as needed -->
                </tbody>
            </table>
        </div>
        </div>





        </div>
    </main>
    <!-- end: Main -->

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="js/chart.js"></script>
    <script src="{{ asset('js/imagefile.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script>
        var categorie = document.querySelector("#categorie");
        categorie.addEventListener("change", () => {
            var prix = document.querySelector('#prix');
            if (categorie.selectedIndex != 4) {
                prix.disabled = true;  // Désactive le champ
            } else {
                prix.disabled = false; // Réactive le champ si l'index est 3
            }
        });
    </script>
</body>

</html>
