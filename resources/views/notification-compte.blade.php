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
    <link rel="stylesheet" href="{{ asset('css/notificationcompte.css') }}">
    <title>Supriz - Comptes</title>
</head>


<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">

    <!-- start: nSidebar -->
    @include('components.sidebar')
    <!-- end: Sidebar -->

    <!-- start: Main -->


    <main class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20 ">

        <div class="pt-6 pb-0 pl-6 pr-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex items-center justify-between mb-5">
                <img src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="">
                <h4 class="font-bold text-right text-white">Messages</h4>
                <div class="flex">
                    <img class="mr-3" src="{{ asset('/images/svg/menu-alt_svgrepo.com.svg') }}" alt="">

                    <div class="flex">
                        <input type="text" class="pr-10 rounded-20" id="search" placeholder="Recherche">
                        <img class="absolute cursor-pointer search_icon"
                            src="{{ asset('/images/svg/search-alt-1_svgrepo.com.svg') }}" alt="">
                    </div>
                </div>
            </div>

        </div>

        <div class="flex mt-3"><a href="{{ route('notificationVente') }}"><span class="mr-4 cursor-pointer">Notification
                    ventes</span></a><a href="{{ route('notificationSupriz') }}"><span
                    class="mr-4 cursor-pointer">Notification Suppriz</span></a><a
                href="{{ route('notificationCompte') }}"><span
                    class="cursor-pointer border-bottom-design text-color-design">Notification Compte Client</span></a>
        </div>
        <hr>
        <div id="user-list">
            @include('partials.user-list', ['users' => $users]) <!-- Inclure la vue partielle ici -->
        </div>

        </div>
    </main>
    <!-- end: Main -->
    <script>
        document.getElementById('search').addEventListener('input', function () {
            let query = this.value;
    
            // Requête AJAX pour récupérer les résultats filtrés
            fetch(`{{ route('search.users') }}?query=${query}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('user-list').innerHTML = html;
                })
                .catch(error => console.log(error));
        });
    </script>
    
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="js/chart.js"></script>
    <script src="js/script.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>

</body>

</html>
