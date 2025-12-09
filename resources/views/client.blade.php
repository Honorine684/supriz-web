<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href={{ asset('css/main.css') }}>
    <link rel="shortcut icon" href="{{asset('/images/svg/llogosupriz2.svg')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Supriz - Clients</title>
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
                <h4 class="font-bold text-right text-white">Liste des clients</h4>
                <div class="flex">
                    <img class="mr-3" src="{{ asset('/images/svg/menu-alt_svgrepo.com.svg') }}" alt="">

                    <div class="flex">
                        <input type="text" class="rounded-20 pr-10" id="search-client" placeholder="Recherche">
                        <img class="absolute cursor-pointer search_icon" src="{{ asset('/images/svg/search-alt-1_svgrepo.com.svg') }}" alt="">
                    </div>
                </div>
            </div>
            <div class="display-flex">
                <table class="w-full">
                    <thead>
                        <tr>
                            <td class="px-4 py-2">
                                <span class="text-[13px] font-medium text-white">Photo de profil</span>
                            </td>

                            <td class="px-4 py-2">
                                <span class="text-[13px] font-medium text-white relative -left-25 text-left">Nom</span>
                            </td>

                            <td class="px-4 py-2">
                                <span
                                    class="text-[13px] font-medium text-white relative -left-25 text-left">Prenom</span>
                            </td>

                            <td class="px-4 py-2">
                                <span
                                    class="text-[13px] font-medium text-white relative -left-25 text-left">Numero</span>
                            </td>
                            <td class="px-4 py-2">
                                <span
                                    class="text-[13px] font-medium text-white relative -left-25 text-left">Email</span>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="p-3 overflow-x-auto bg-white border border-gray-100 shadow-md rounded-20 shadow-black/5 lg:col-span-2">
            <table class="w-full">        
                <!-- Dynamic content -->
                <tbody id="client-list">
                    @include('partials.client-list', ['users' => $users_])
                </tbody>
                
            </table>
        </div>
        


        </div>
    </main>
    <!-- end: Main -->
    <script>
document.getElementById('search-client').addEventListener('input', function () {
    let query = this.value;

    // AJAX request to search clients
    fetch(`{{ route('admin.search.clients') }}?query=${query}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('client-list').innerHTML = html;

            // If no results, display message
            if (html.trim() === '') {
                document.getElementById('client-list').innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-gray-500 py-4">Aucun client trouvé</td>
                    </tr>`;
            }
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
