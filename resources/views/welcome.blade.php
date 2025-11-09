<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href={{ asset('css/main.css') }}>
    <link rel="shortcut icon" href="{{asset('/images/svg/llogosupriz2.svg')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Supriz - Dashboard</title>
</head>


<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">

    <!-- start: Sidebar -->
    @include("components.sidebar")
    <!-- end: Sidebar -->

    <!-- start: Main -->
    <main
        class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20 ">
        <button type="button" class="text-lg text-gray-600 sidebar-toggle md:hidden">
            <i class="ri-menu-line"></i>
        </button>
        <h2 class="font-bold">John Doe</h2>
        <div class="p-6">
            <div class="flex justify-between mb-6 f-wrap">
                <!-- Première carte -->
                <div
                    class="w-full p-6 mb-4 border border-gray-100 shadow-md sm:w-1/2 md:w-1/2 margin-right-8 lg:w-1/4 btn-design rounded-20 padding-bottom-0 shadow-black/5 lg:mr-8">
                    <div class="flex justify-between mb-6">
                        <div>
                            <div class="mb-3 text-white w-80 d-flex display-flex width-86"><img
                                    src="{{ asset('images/svg/wallet_svgrepo.com.svg') }}" alt=""><span
                                    class="font-small position-relative bottom-5">Actif</span></div>
                            <div class="mb-3 font-bold text-white font-bolder-24">CFA {{$actif}}</div>
                            <p class="text-white font-small ">Solde courant de l'entreprise</p>
                        </div>
                    </div>

                </div>

                <!-- Deuxième carte -->
                <div
                    class="w-full p-6 mb-4 bg-white border border-gray-100 shadow-md sm:w-1/2 md:w-1/2 margin-right-8 lg:w-1/4 rounded-20 padding-bottom-0 shadow-black/5 lg:mr-8">
                    <div class="flex justify-between mb-6">
                        <div>
                            <div class="mb-3 text-black w-80 d-flex display-flex width-86"><img
                                    src="{{ asset('images/svg/wallet-send_svgrepo.com.svg') }}" alt=""><span
                                    class="font-small position-relative bottom-5">Dépenses</span></div>
                            <div class="mb-3 font-bold text-color-design font-bolder-24">CFA {{$depenses}}</div>
                            <p class="text-black font-small ">Dépenses de l'entreprise</p>
                        </div>
                    </div>

                </div>

                <!-- Troisième carte -->
                <div class="w-full p-6 mb-4 bg-white border border-gray-100 shadow-md sm:w-1/2 md:w-1/2 margin-right-8 lg:w-1/4 rounded-20 padding-bottom-0 shadow-black/5 lg:mr-8">
                    <div class="flex justify-between mb-6">
                        <div>
                            <div class="mb-3 text-black w-80 d-flex display-flex width-86"><img
                                    src="{{ asset('images/svg/wallet-receive_svgrepo.com.svg') }}" alt=""><span
                                    class="font-small position-relative bottom-5">Ventes</span></div>
                            <div class="mb-3 font-bold text-color-design font-bolder-24">CFA {{$ventes}}</div>
                            <p class="text-black font-small ">Ventes effectuées par l'entreprise</p>
                        </div>
                    </div>

                </div>

                <!-- Quatrième carte -->
                <div
                    class="w-full p-6 mb-4 bg-white border border-gray-100 shadow-md sm:w-1/2 md:w-1/2 margin-right-8 lg:w-1/4 rounded-20 padding-bottom-0 shadow-black/5 lg:mr-8">
                    <div class="flex justify-between mb-6">
                        <div>
                            <div class="mb-3 text-black w-80 d-flex display-flex width-86"><img
                                    src="{{ asset('images/svg/board-performance-profit_svgrepo.com.svg') }}"
                                    alt=""><span class="font-small position-relative bottom-5">Bénéifces</span>
                            </div>
                            <div class="mb-3 font-bold text-color-design font-bolder-24">CFA {{$benefices}}</div>
                            <p class="text-black font-small ">Bénéfices de l'entreprise</p>
                        </div>
                    </div>

                </div>
            </div>





            <div class="flex w-full bg-transparent rounded-lg f-wrap margin-bottom-8 mb-9">
                <div class="p-3 ml-0 rounded margin-right-8">
                    <div class="p-4 mt-6 mb-6 bg-white rounded-20 width-devise-card-stat card">
                        <h3 class="font-bold">Nombres d'abonnés</h3>
                        <div class="mt-3 display-flex">
                            <span class="font-bold text-color-design">{{count($user)}}</span>
                            <span><img src="{{ asset('images/svg/user-plus-alt-1_svgrepo.com.svg') }}"
                                    alt=""></span>
                        </div>
                    </div>

                    <div class="p-4 mt-6 mb-6 bg-white rounded-20 width-devise-card-stat card">
                        <h3 class="font-bold">Clients perdus</h3>
                        <div class="mt-3 display-flex">
                            <span class="font-bold text-color-design">{{count($lostClients)}}</span><span><img
                                    src="{{ asset('images/svg/user-minus-alt-1_svgrepo.com.svg') }}"
                                    alt=""></span>
                        </div>
                    </div>

                    <div class="p-4 mt-6 mb-6 bg-white rounded-20 width-devise-card-stat card">
                        <h3 class="font-bold">Clients actifs</h3>
                        <div class="mt-3 display-flex">
                            <span class="font-bold text-color-design">{{count($activeClients)}}</span><span><img
                                    src="{{ asset('images/svg/Vector.svg') }}" alt=""></span>
                        </div>
                    </div>
                </div>

                <div class="w-full p-6 bg-white rounded-20 mb-9">
                    <div class="mb-6 display-flex">
                        <h3 class="font-bold">Suivie de revenu</h3>
                        <h3>Analyse du revenu annuel</h3>
                    </div>
                    <div id="line-chart"></div>
                </div>



            </div>

            <div class="p-6 overflow-x-auto lg:col-span-2">
                <div class="display-flex">
                    <table class="w-full min-w-[540px]">
                        <thead>
                            <tr>
                                <th
                                    class="text-[12px] uppercase tracking-wide  text-black font-bold py-2 px-4 bg-gray-50 text-left rounded-tl-md rounded-bl-md">
                                    Nouveaux clients</th>
                                <th
                                    class="text-[12px] uppercase tracking-wide  text-black font-bold py-2 px-4 bg-gray-50 text-left">
                                    {{ now()->format('d/m/Y') }}

                                </th>
                                <th class="text-[12px] uppercase tracking-wide  text-black font-bold py-2 px-4 bg-gray-50 text-left">
                                    {{count($userToday)}}
                                </th>

                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <div
                class="p-6 overflow-x-auto bg-white border border-gray-100 shadow-md rounded-20 shadow-black/5 lg:col-span-2">
                <table class="w-full min-w-[540px]">

                    <tbody>
                        @foreach ($userToday as $item)
                        <tr>
                            <td class="px-4 py-2 border-b border-b-gray-50">
                                <div class="flex items-center">
                                    @if (auth()->user() && auth()->user()->profil_image)
                                    <img class="profil-photo" src="{{ asset('storage/' . auth()->user()->profil_image) }}" alt="">
                                @else
                                    <img class="profil-photo" src="{{ asset('images/profil/no-profile.png') }}" alt="">
                                @endif
                                    <a href="#"
                                        class="ml-2 text-sm font-medium text-gray-600 truncate hover:text-blue-500">{{$item->nom}}
                                        {{$item->prenom}}</a>
                                </div>
                            </td>
                            <td class="px-4 py-2 border-b border-b-gray-50">
                                <span class="text-[13px] font-medium text-gray-400">{{$item->email}}</span>
                            </td>

                            <td class="px-4 py-2 border-b border-b-gray-50">
                                <span class="text-[13px] font-medium text-gray-400">{{$item->created_at}}</span>
                            </td>
                        </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>


        </div>
    </main>
    <!-- end: Main -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = @json($data);

            const options = {
                chart: {
                    height: "300px",
                    width: "100%",
                    type: "line",
                    fontFamily: "Inter, sans-serif",
                    dropShadow: {
                        enabled: false,
                    },
                    toolbar: {
                        show: true,
                    },
                },
                tooltip: {
                    enabled: true,
                    x: {
                        show: true,
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    width: 6,
                },
                grid: {
                    show: true,
                    strokeDashArray: 4,
                    padding: {
                        left: 2,
                        right: 2,
                        top: -26
                    },
                },
                series: [
                    {
                        name: "Total Mensuel",
                        data: chartData.totals,
                        color: "#00395F",
                    }
                ],
                legend: {
                    show: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    categories: chartData.months,
                    labels: {
                        show: true,
                        style: {
                            fontFamily: "Inter, sans-serif",
                            cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                        }
                    },
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                },
                yaxis: {
                    show: false,
                },
            }

            if (document.getElementById("line-chart") && typeof ApexCharts !== 'undefined') {
                const chart = new ApexCharts(document.getElementById("line-chart"), options);
                chart.render();
            }
        });
    </script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="js/script.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>

</body>

</html>
