<!doctype html>
<html lang="en">

<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href={{ asset('css/main.css') }}>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Supriz - Dépenses</title>
</head>


<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">
    @include('partials.gtm-body')

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


        <form class="mt-5"
            action="{{ isset($depenseEdit) ? route('admin.depenses.update', $depenseEdit->id) : route('admin.depenses.store') }}"
            method="POST">
            @csrf
            @if (isset($depenseEdit))
                @method('PUT')
            @endif

            <h2 class="font-bold mb-4">{{ isset($depenseEdit) ? 'Modifier dépense' : 'Ajouter dépense' }}</h2>

            <!-- Dépense -->
            <div class="mb-4">
                <label for="depense" class="block text-sm font-medium text-gray-700 mb-2">Dépenses</label>
                <input type="text" name="depenses" id="depense" class="block w-full p-2 border rounded"
                    placeholder="Votre dépense"
                    value="{{ isset($depenseEdit) ? $depenseEdit->depenses : old('depenses') }}">
                @error('depenses')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Prix -->
            <div class="mb-4">
                <label for="prix" class="block text-sm font-medium text-gray-700 mb-2">Prix</label>
                <input type="number" name="prix" id="prix" class="block w-full p-2 border rounded"
                    placeholder="Prix de votre dépense"
                    value="{{ isset($depenseEdit) ? $depenseEdit->prix : old('prix') }}">
                @error('prix')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div>
                <input type="submit" name="submit"
                    class="w-full relative right-0 rounded p-2 text-white cursor-pointer btn-design mt-1"
                    value="{{ isset($depenseEdit) ? 'Modifier' : 'Enregistrer' }}">
            </div>
        </form>




        <p class="mt-3 mb-2">Liste des Dépenses</p>

        <div
            class="p-3 overflow-x-auto bg-white border border-gray-100 shadow-md rounded-20 shadow-black/5 lg:col-span-2">
            <table class="w-full">

                <tbody>
                    @if (!$depenses->isEmpty())

                        @foreach ($depenses as $depense)
                            <!-- Continue this pattern for the rest of the rows -->
                            <tr>
                                <td class="px-4 py-2 border-b w-60 border-b-gray-50">
                                    <span
                                        class="text-[13px] font-medium text-gray-400">{{ $depense['depenses'] }}</span>
                                </td>

                                <td class="px-4 py-2 border-b w-60 border-b-gray-50">
                                    <span class="text-[13px] font-medium text-gray-400">{{ $depense['prix'] }}
                                        FCFA</span>
                                </td>

                                                                <td class="px-4 py-2 border-b w-60 border-b-gray-50">
                                    <span class="text-[13px] font-medium text-gray-400">{{ $depense->created_at->format("d-m-y") }}
                                        </span>
                                </td>

                                <td class="flex px-4 py-2 ">
                                    <a href="{{ route('admin.depenses.edit', $depense->id) }}"><span
                                            class="mr-2 text-[13px] font-medium text-gray-400 cursor-pointer"><img
                                                class="relative top-1"
                                                src="{{ asset('/images/svg/edit-3_svgrepo.com.svg') }}"
                                                alt="">
                                            </span>


                                    </a>

                                    <form action="{{ route('admin.depenses.destroy', $depense->id) }}" method="POST"
                                        class="text-[13px] font-medium text-gray-400 cursor-pointer">
                                        @csrf
                                        @method('DELETE')
                                        <!-- Cette méthode est utilisée pour les requêtes de suppression -->
                                        <button type="submit"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ces Depenses ?')">
                                            <span><img class="relative top-1"
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

</body>

</html>
