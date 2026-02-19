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

<!-- Bouton flottant pour ouvrir la popup - Couleur Supriz -->
<button onclick="openNotificationModal()" id="openModalBtn"
    style="position: fixed; bottom: 30px; right: 30px; z-index: 9999; background-color: #005792; color: white; border: none; border-radius: 50%; width: 60px; height: 60px; cursor: pointer; box-shadow: 0 4px 15px rgba(0, 87, 146, 0.4); display: flex; align-items: center; justify-content: center;">
    <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
    </svg>
</button>

<!-- Modal/Popup pour le formulaire de notification -->
<div id="notificationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99999;">
    <!-- Overlay sombre -->
    <div onclick="closeNotificationModal()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);"></div>

    <!-- Contenu de la modal -->
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90%; max-width: 500px; background: white; border-radius: 16px; padding: 24px; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 20px; font-weight: bold; color: #005792; margin: 0;">Nouvelle notification</h3>
            <button onclick="closeNotificationModal()" style="background: none; border: none; cursor: pointer; padding: 5px;">
                <svg style="width: 24px; height: 24px; color: #6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Formulaire -->
        <form action="{{route('admin.notification.store')}}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px;">Titre</label>
                <input type="text" name="titre" placeholder="Titre de la notification"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 12px; font-size: 14px; outline: none; box-sizing: border-box;" required>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px;">Description</label>
                <textarea name="contenus" placeholder="Description de la notification..." rows="4"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 12px; font-size: 14px; outline: none; resize: none; box-sizing: border-box;" required></textarea>
            </div>
            <button type="submit" name="submit"
                style="width: 100%; padding: 14px; background-color: #005792; color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                Envoyer la notification
            </button>
        </form>
    </div>
</div>

<script>
    function openNotificationModal() {
        document.getElementById('notificationModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeNotificationModal() {
        document.getElementById('notificationModal').style.display = 'none';
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
