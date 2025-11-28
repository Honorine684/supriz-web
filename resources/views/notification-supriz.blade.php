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
    <title>Supriz - Supriz </title>
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

        <div class="flex mt-3"><a href="{{ route('admin.notificationVente') }}"><span class="mr-4 cursor-pointer">Notification
                    ventes</span></a><a href="{{ route('admin.notificationSupriz') }}"><span
                    class="mr-4 cursor-pointer border-bottom-design text-color-design">Notification Suppriz</span></a><a
                href="{{ route('admin.notificationCompte') }}"><span class="cursor-pointer ">Notification Compte
                    Client</span></a></div>
        <hr>

        @if ($errors->any())
        <div class="bg-red-500 mt-2 text-center p-2 rounded text-white">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    
    @if (session('success'))
        <div class="bg-green-500 mt-2 text-center p-2 rounded text-white">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    
    @if (session('error'))
        <div class="bg-red-500 text-center p-2 rounded text-white">
            <p>{{ session('error') }}</p>
        </div>
    @endif
        <div class="container mx-auto p-4">

            <!-- Supriz en cours -->
            <span class="p-5 mt-4 mb-4 relative rounded border w-full cursor-pointer block selected_bord">Supriz en
                cours @if (count($supriz_encours) > 0)
                    ({{ count($supriz_encours) }})
                @else
                    {{ '(0)' }}
                @endif </span>
            <div class="selected_bord_body hidden">
                @if ($supriz_encours->isEmpty())
                    <p>Aucune supriz en cours...</p>
                @else
                    @foreach ($supriz_encours as $supriz)
                        <div class="p-3 mt-4 bg-gray-300 rounded">
                            <div class="flex items-center justify-between">
                                <span class="text-green-500">Numero de message supriz : S{{$supriz->id}}</span>
                                <span>{{ now()->format('d/m/Y à H:i') }}</span>
                            </div>

                            <div class="flex items-center justify-between w-full mt-3 mb-3 ">
                                <span>Utilisateur :</span>
                                <span>{{ $supriz->user ? $supriz->user->nom : 'Utilisateur non spécifié' }}</span>
                            </div>

                            <div class="max-w-4xl mx-auto">
                                <table class="w-full text-left bg-white border border-gray-300 table-auto">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            <th class="w-1/4 px-4 py-2">Suppriz</th>
                                            <th class="w-1/6 px-4 py-2">Destinataire</th>
                                            <th class="w-1/6 px-4 py-2">Numéro de l'acheteur</th>
                                            <th class="w-1/3 px-4 py-2">Explication</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="px-4 py-2 border">{{ $supriz->nom_categorie_supriz }}</td>
                                            <td class="px-4 py-2 border">
                                                {{ $supriz->numero_destinateur ? $supriz->numero_destinateur : 'Non spécifié' }}
                                            </td>
                                            <td class="px-4 py-2 border">
                                                {{ $supriz->user ? $supriz->user->numero : 'Non spécifié' }}</td>
                                            <td class="px-4 py-2 text-sm border">{{ $supriz->Description_supriz }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="bg-gray-200">
                                        <tr>
                                            <td colspan="3" class="px-4 py-2 font-bold text-right">Prix total :</td>
                                            <td class="px-4 py-2 font-bold">
                                                {{ number_format($supriz->prix, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <form action="{{route('admin.close.supriz')}}" method="POST">
                                @csrf
                                <input type="hidden" name="supriz_id" value="{{$supriz->id}}">
                                <button class="relative w-full p-1 mt-4 text-white bg-green-600 rounded" type="submit">Validé</button>
                            </form>
                        </div>
                    @endforeach

                @endif
            </div>

            <!-- Supriz livrées -->
            <span class="p-5 mt-4 mb-4 relative rounded border w-full cursor-pointer block selected_bord">Supriz livrées @if (count($supriz_termine) > 0)
                    ({{ count($supriz_termine) }})
                @else
                    {{ '(0)' }}
                @endif </span>
            <div class="selected_bord_body hidden">
                @if ($supriz_termine->isEmpty())
                    <p>Aucune supriz livrée...</p>
                @else
                    @foreach ($supriz_termine as $supriz)
                        <div class="p-3 mt-4 bg-gray-300 rounded">
                            <div class="flex items-center justify-between">
                                <span class="text-green-500">Numero de message supriz : S{{$supriz->id}}</span>
                                <span>{{ now()->format('d/m/Y à H:i') }}</span>
                            </div>

                            <div class="flex items-center justify-between w-full mt-3 mb-3 ">
                                <span>Utilisateur :</span>
                                <span>{{ $supriz->user ? $supriz->user->nom : 'Utilisateur non spécifié' }}</span>
                            </div>

                            <div class="max-w-4xl mx-auto">
                                <table class="w-full text-left bg-white border border-gray-300 table-auto">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            <th class="w-1/4 px-4 py-2">Suppriz</th>
                                            <th class="w-1/6 px-4 py-2">Destinataire</th>
                                            <th class="w-1/6 px-4 py-2">Numéro de l'acheteur</th>
                                            <th class="w-1/3 px-4 py-2">Explication</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="px-4 py-2 border">{{ $supriz->nom_categorie_supriz }}</td>
                                            <td class="px-4 py-2 border">
                                                {{ $supriz->numero_destinateur ? $supriz->numero_destinateur : 'Non spécifié' }}
                                            </td>
                                            <td class="px-4 py-2 border">
                                                {{ $supriz->user ? $supriz->user->numero : 'Non spécifié' }}</td>
                                            <td class="px-4 py-2 text-sm border">{{ $supriz->Description_supriz }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="bg-gray-200">
                                        <tr>
                                            <td colspan="3" class="px-4 py-2 font-bold text-right">Prix total :</td>
                                            <td class="px-4 py-2 font-bold">
                                                {{ number_format($supriz->prix, 0, ',', ' ') }}
                                                FCFA</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    @endforeach

                @endif
            </div>

           



        </div>
    </main>
    <!-- end: Main -->

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('js/showblock.js') }}"></script>
    <script src="js/script.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>

</body>

</html>
