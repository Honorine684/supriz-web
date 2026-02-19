<!DOCTYPE html>
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
    <title>Supriz - Messages</title>
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

    <div class="flex mt-3">
        <a href="{{ route('admin.notifications') }}"><span class="mr-4 cursor-pointer border-bottom-design text-color-design">Rédiger une
            notification</span></a>
        <a href="{{ route('admin.publicite') }}"><span class="cursor-pointer ">Faire une publicité</span></a>
    </div>
    <hr>

    <div class="pb-40 overflow-y-auto contener h-120">
        @if ($notifs)
            @foreach ($notifs as $notif)
                <div class="flex mt-4">
                    @if ($user->profil_image)
                        <img class="relative mr-2 profil-photo top-4"
                            src="{{ asset('storage/' . $user->profil_image) }}" alt="">
                    @else
                        <img class="relative mr-2 profil-photo top-4"
                            src="{{ asset('images/profil/no-profile.png') }}" alt="">
                    @endif
                    <div class="p-2 font-light bg-gray-primary rounded-20">
                        @if($notif->titre)
                            <p class="font-semibold">{{ $notif->titre }}</p>
                        @endif
                        <p>{{ $notif->contenus }}</p>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center">Aucune données disponible</p>
        @endif

        <form action="{{route('admin.notification.store')}}" method="POST">
            @csrf
            <div class="fixed left-0 right-0 md:left-64 bottom-0 p-4 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col gap-3 max-w-4xl mx-auto">
                    <input type="text" name="titre" placeholder="Titre de la notification"
                        class="w-full border-none rounded-xl bg-gray-200 py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <div class="flex gap-3 items-end">
                        <textarea name="contenus" placeholder="Description de la notification.." rows="2"
                            class="flex-1 border-none rounded-xl resize-none bg-gray-200 py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                        <button type="submit" name="submit" class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-3 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

</main>

    <!-- end: Main -->

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="js/chart.js"></script>
    <script src="js/script.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>

</body>

</html>
