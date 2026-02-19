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

    <div class="pb-48 overflow-y-auto contener" style="min-height: calc(100vh - 200px);">
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
    </div>

</main>

<!-- Bouton flottant pour ouvrir la popup -->
<button onclick="openNotificationModal()" id="openModalBtn"
    style="position: fixed; bottom: 30px; right: 30px; z-index: 9999; background-color: #2563eb; color: white; border: none; border-radius: 50%; width: 60px; height: 60px; cursor: pointer; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4); display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
    <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
    </svg>
</button>

<!-- Modal/Popup pour le formulaire de notification -->
<div id="notificationModal" class="hidden" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 99999;">
    <!-- Overlay sombre -->
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="closeNotificationModal()"></div>

    <!-- Contenu de la modal -->
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-lg mx-4">
        <div class="bg-white rounded-2xl shadow-2xl p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800">Nouvelle notification</h3>
                <button onclick="closeNotificationModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Formulaire -->
            <form action="{{route('admin.notification.store')}}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                        <input type="text" name="titre" placeholder="Titre de la notification"
                            class="w-full border border-gray-300 rounded-xl bg-gray-50 py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="contenus" placeholder="Description de la notification..." rows="4"
                            class="w-full border border-gray-300 rounded-xl resize-none bg-gray-50 py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required></textarea>
                    </div>
                    <button type="submit" name="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl py-3 px-4 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Envoyer la notification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openNotificationModal() {
        document.getElementById('notificationModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeNotificationModal() {
        document.getElementById('notificationModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Fermer avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeNotificationModal();
        }
    });
</script>

    <!-- end: Main -->

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="js/chart.js"></script>
    <script src="js/script.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>

</body>

</html>
