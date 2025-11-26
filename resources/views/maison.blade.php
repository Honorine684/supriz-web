<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href={{ asset('css/main.css') }}>
    <link rel="shortcut icon" href="{{asset('/images/svg/llogosupriz2.svg')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Supriz - Maison</title>
    <style>
        body {
            background: #e5e7eb;
        }

        .main-container {
            padding: 24px;
        }

        .admin-header {
            color: #111827;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        /* Cartes de statistiques du haut */
        .stats-row {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 180px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .stat-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-title {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-card-bottom {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .stat-number {
            font-size: 40px;
            font-weight: 700;
            color: #1e40af;
            line-height: 1;
        }

        .stat-variation {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 14px;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 8px;
            background: #d1fae5;
            color: #059669;
        }

        /* Section métriques + graphique */
        .middle-section {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .metrics-column {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 16px;
            flex: 0 0 auto;
            width: 160px;
        }

        .metric-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 16px;
            flex: 1;
        }

        .metric-title {
            font-size: 13px;
            color: #111827;
            font-weight: 600;
        }

        .metric-bottom {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .metric-value {
            font-size: 32px;
            font-weight: 700;
            color: #1e40af;
            line-height: 1;
        }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        /* Graphique */
        .chart-section {
            flex: 1;
            min-width: 400px;
        }

        .chart-container {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .chart-subtitle {
            font-size: 13px;
            color: #6b7280;
        }

        /* Table utilisateurs */
        .users-table {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .table-info {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .table-date {
            font-size: 14px;
            color: #6b7280;
        }

        .table-count {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .metrics-column {
                width: 100%;
                flex-direction: row;
                gap: 16px;
            }

            .metric-card {
                flex: 1;
                min-width: 150px;
            }

            .middle-section {
                flex-direction: column;
            }

            .chart-section {
                min-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 16px;
            }

            .admin-header {
                font-size: 24px;
                margin-bottom: 16px;
            }

            .stats-row {
                gap: 12px;
            }

            .stat-card {
                min-width: 150px;
            }

            .metrics-column {
                flex-direction: column;
            }

            .metric-card {
                min-width: 100%;
            }
        }

        @media (max-width: 640px) {
            .stats-row {
                flex-direction: column;
            }

            .stat-card {
                min-width: 100%;
            }

            .stat-number {
                font-size: 32px;
            }

            .metric-value {
                font-size: 28px;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .chart-container {
                padding: 16px;
            }

            .users-table {
                padding: 16px;
            }
        }

        @media (max-width: 480px) {
            .stat-card-top {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .stat-icon {
                align-self: flex-end;
            }

            .chart-section {
                min-width: 280px;
            }
        }
    </style>
</head>

<body class="text-gray-800 font-inter" style="background: #e5e7eb;">

    <!-- start: Sidebar -->
    @include("components.sidebar")
    <!-- end: Sidebar -->

    <!-- start: Main -->
    <main class="w-full md:w-[calc(100%-256px)] md:ml-64 min-h-screen transition-all">
        <div class="main-container">
            <!-- Bouton Menu Mobile -->
            <button type="button" class="mb-4 text-lg text-gray-600 sidebar-toggle md:hidden">
                <i class="ri-menu-line"></i>
            </button>

            <!-- Header -->
            <h1 class="admin-header">Admin</h1>

            <!-- Statistiques par Catégorie -->
            <div class="stats-row">
                @php
                    $categories = [
                        'Mariage' => ['icon' => '💍', 'color' => '#dbeafe'],
                        'Vœux' => ['icon' => '🎁', 'color' => '#dbeafe'],
                        'Anniversaire' => ['icon' => '🎂', 'color' => '#dbeafe'],
                        'Nouvel an' => ['icon' => '🎊', 'color' => '#dbeafe'],
                        'Noël' => ['icon' => '🎄', 'color' => '#dcfce7'],
                        'mots' => ['icon' => '💌', 'color' => '#fef3c7']
                    ];
                @endphp

                @foreach($statsSupriz as $categorie => $stats)
                @if(isset($categories[$categorie]))
                <div class="stat-card">
                    <div class="stat-card-top">
                        <h3 class="stat-title">{{ $categorie }}</h3>
                        <span class="stat-icon" style="background-color: {{ $categories[$categorie]['color'] }};">
                            {{ $categories[$categorie]['icon'] }}
                        </span>
                    </div>
                    <div class="stat-card-bottom">
                        <span class="stat-number">{{ $stats['total'] }}</span>
                        <span class="stat-variation">
                            <i class="ri-arrow-up-line"></i>
                            +{{ abs($stats['variation']) }}
                        </span>
                    </div>
                </div>
                @endif
                @endforeach
            </div>

            <!-- Métriques + Graphique -->
            <div class="middle-section">
                <!-- Colonne des métriques -->
                <div class="metrics-column">
                    <div class="metric-card">
                        <div class="metric-title">Nombres d'abonnés</div>
                        <div class="metric-bottom">
                            <span class="metric-value">{{ $nombresAbonnes }}</span>
                            <div class="metric-icon" style="background-color: #d1fae5;">
                                <i class="text-green-600 ri-user-add-line"></i>
                            </div>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-title">Nombre de clique</div>
                        <div class="metric-bottom">
                            <span class="metric-value">{{ $nombreClics }}</span>
                            <div class="metric-icon" style="background-color: #fef3c7;">
                                <i class="text-yellow-600 ri-cursor-line"></i>
                            </div>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-title">Clients actifs</div>
                        <div class="metric-bottom">
                            <span class="metric-value">{{ $clientsActifs }}</span>
                            <div class="metric-icon" style="background-color: #d1fae5;">
                                <i class="text-green-600 ri-user-star-line"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphique -->
                <div class="chart-section">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Activité du site</h3>
                            <span class="chart-subtitle">Analyse l'activité mensuel du site</span>
                        </div>
                        <div id="activity-chart"></div>
                    </div>
                </div>
            </div>

            <!-- Utilisateurs Récents -->
            <div class="users-table">
                <div class="table-header">
                    <h3 class="table-title">Utilisateurs récents</h3>
                    <div class="table-info">
                        <span class="table-date">{{ now()->format('d/m/Y') }}</span>
                        <span class="table-count">{{ count($utilisateursRecents) }}</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <tbody class="divide-y divide-gray-100">
                            @foreach($utilisateursRecents as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-4">
                                        @if($user['avatar'])
                                            <img class="user-avatar" src="{{ asset('storage/' . $user['avatar']) }}" alt="">
                                        @else
                                            <div class="flex items-center justify-center text-white bg-blue-500 user-avatar">
                                                {{ substr($user['nom'], 0, 1) }}
                                            </div>
                                        @endif
                                        <span class="font-medium text-gray-900">{{ $user['nom'] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="text-sm text-gray-600">{{ $user['categorie'] }}</span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <span class="text-sm text-gray-600">{{ $user['date'] }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <!-- end: Main -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const activityData = {!! json_encode($activityData) !!};

            const options = {
                chart: {
                    height: "320px",
                    width: "100%",
                    type: "line",
                    fontFamily: "Inter, sans-serif",
                    dropShadow: {
                        enabled: false,
                    },
                    toolbar: {
                        show: false,
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
                    width: 3,
                    curve: 'smooth'
                },
                grid: {
                    show: true,
                    strokeDashArray: 4,
                    borderColor: '#e5e7eb',
                    padding: {
                        left: 10,
                        right: 10,
                        top: -20
                    },
                },
                series: [{
                    name: "Messages Supriz",
                    data: activityData.totals,
                    color: "#2563eb",
                }],
                legend: {
                    show: false
                },
                xaxis: {
                    categories: activityData.months,
                    labels: {
                        show: true,
                        style: {
                            fontFamily: "Inter, sans-serif",
                            fontSize: '11px',
                            colors: '#9ca3af'
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
                    show: true,
                    labels: {
                        style: {
                            fontFamily: "Inter, sans-serif",
                            fontSize: '11px',
                            colors: '#9ca3af'
                        }
                    }
                },
            }

            if (document.getElementById("activity-chart") && typeof ApexCharts !== 'undefined') {
                const chart = new ApexCharts(document.getElementById("activity-chart"), options);
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
