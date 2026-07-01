<!DOCTYPE html>
<html lang="fr">

<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href={{ asset('css/main.css') }}>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
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
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 10px;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        .stats-row::-webkit-scrollbar {
            height: 8px;
        }

        .stats-row::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .stats-row::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .stats-row::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            flex: 0 0 auto;
            min-width: 200px;
            width: 200px;
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

        .stat-variation.down {
            background: #fee2e2;
            color: #dc2626;
        }

        .stat-variation.neutral {
            background: #f3f4f6;
            color: #6b7280;
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
    @include('partials.gtm-body')

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

            <!-- Header + Toggle -->
            <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
                <h1 class="admin-header !mb-0">Admin</h1>

                <div class="dashboard-toggle" data-active="supriz">
                    <div class="toggle-slider"></div>
                    <button type="button" class="toggle-opt" data-view="supriz">
                        <i class="ri-heart-line"></i>
                        <span>Supriz</span>
                    </button>
                    <button type="button" class="toggle-opt" data-view="ecom">
                        <i class="ri-shopping-cart-2-line"></i>
                        <span>E-commerce</span>
                    </button>
                </div>
            </div>

            <style>
                .dashboard-toggle {
                    display: inline-flex;
                    position: relative;
                    background: #fff;
                    border: 1px solid #e5e7eb;
                    border-radius: 999px;
                    padding: 4px;
                    box-shadow: 0 2px 8px rgba(0,0,0,.04);
                }
                .dashboard-toggle .toggle-opt {
                    position: relative; z-index: 2;
                    display: inline-flex; align-items: center; gap: 8px;
                    padding: 10px 22px; border: none; background: transparent;
                    color: #6b7280; font-weight: 600; font-size: 14px;
                    cursor: pointer;
                    transition: color .3s ease;
                }
                .dashboard-toggle .toggle-opt i { font-size: 16px; }
                .dashboard-toggle[data-active="supriz"] .toggle-opt[data-view="supriz"],
                .dashboard-toggle[data-active="ecom"] .toggle-opt[data-view="ecom"] {
                    color: #fff;
                }
                .toggle-slider {
                    position: absolute; top: 4px; bottom: 4px;
                    left: 4px; width: calc(50% - 4px);
                    background: linear-gradient(135deg, #005792 0%, #0077b6 100%);
                    border-radius: 999px; z-index: 1;
                    transition: transform .35s cubic-bezier(.4,0,.2,1);
                    box-shadow: 0 2px 8px rgba(0,87,146,.3);
                }
                .dashboard-toggle[data-active="ecom"] .toggle-slider {
                    transform: translateX(100%);
                }
                .dash-view {
                    animation: fadeSlideIn .4s cubic-bezier(.4,0,.2,1);
                }
                .dash-view.hidden { display: none; }
                @keyframes fadeSlideIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                .ecom-stat-card {
                    background: #fff; border-radius: 16px; padding: 20px;
                    border: 1px solid #e5e7eb;
                    display: flex; align-items: center; gap: 14px;
                }
                .ecom-stat-card .icon {
                    width: 48px; height: 48px; border-radius: 12px;
                    display: flex; align-items: center; justify-content: center;
                    font-size: 22px;
                }
                .ecom-stat-card .value {
                    font-size: 22px; font-weight: 700; color: #111827;
                }
                .ecom-stat-card .label {
                    font-size: 12px; color: #6b7280; font-weight: 500;
                }
            </style>

            {{-- ============================================================ --}}
            {{-- VUE SUPRIZ (par défaut) --}}
            {{-- ============================================================ --}}
            <div class="dash-view" id="view-supriz">

            <!-- Statistiques par Catégorie -->
            <div class="stats-row">
                @php
                    // Icônes et couleurs par défaut pour les catégories connues
                    $categoriesDefaut = [
                        'Mariage' => ['icon' => '💍', 'color' => '#dbeafe'],
                        'Vœux' => ['icon' => '🎁', 'color' => '#dbeafe'],
                        'Anniversaire' => ['icon' => '🎂', 'color' => '#dbeafe'],
                        'Nouvel an' => ['icon' => '🎊', 'color' => '#dbeafe'],
                        'Noël' => ['icon' => '🎄', 'color' => '#dcfce7'],
                        'mots' => ['icon' => '💌', 'color' => '#fef3c7']
                    ];

                    // Icônes et couleurs pour les nouvelles catégories
                    $iconesAutres = ['🎉', '✨', '🌟', '💝', '🎈', '🎀', '🌺', '🌈', '⭐', '💫'];
                    $couleursAutres = ['#fce7f3', '#f3e8ff', '#ddd6fe', '#e0e7ff', '#fef3c7', '#d1fae5', '#fef08a', '#fed7aa'];
                    $indexIcone = 0;
                @endphp

                @foreach($statsSupriz as $categorie => $stats)
                @php
                    // Utiliser l'icône/couleur prédéfinie ou générer une nouvelle
                    if (isset($categoriesDefaut[$categorie])) {
                        $icon = $categoriesDefaut[$categorie]['icon'];
                        $color = $categoriesDefaut[$categorie]['color'];
                    } else {
                        $icon = $iconesAutres[$indexIcone % count($iconesAutres)];
                        $color = $couleursAutres[$indexIcone % count($couleursAutres)];
                        $indexIcone++;
                    }
                @endphp
                <div class="stat-card">
                    <div class="stat-card-top">
                        <h3 class="stat-title">{{ $categorie }}</h3>
                        <span class="stat-icon" style="background-color: {{ $color }};">
                            {{ $icon }}
                        </span>
                    </div>
                    <div class="stat-card-bottom">
                        <span class="stat-number">{{ $stats['total'] }}</span>
                        @if($stats['variation'] > 0)
                            <span class="stat-variation">
                                <i class="ri-arrow-up-line"></i>
                                +{{ $stats['variation'] }}
                            </span>
                        @elseif($stats['variation'] < 0)
                            <span class="stat-variation down">
                                <i class="ri-arrow-down-line"></i>
                                {{ $stats['variation'] }}
                            </span>
                        @else
                            <span class="stat-variation neutral">
                                <i class="ri-subtract-line"></i>
                                0
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Métriques + Graphique -->
            <div class="middle-section">
                <!-- Colonne des métriques -->
                <div class="metrics-column">
                    <div class="metric-card">
                        <div class="metric-title">Nombre d'utilisateurs</div>
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
                        <thead>
                            <tr class="text-left text-gray-500 text-sm border-b border-gray-200">
                                <th class="px-4 py-3 font-medium">Utilisateur</th>
                                <th class="px-4 py-3 font-medium text-center">Catégorie</th>
                                <th class="px-4 py-3 font-medium text-right">Date et heure (partie client)</th>
                            </tr>
                        </thead>
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

            </div> {{-- END #view-supriz --}}

            {{-- ============================================================ --}}
            {{-- VUE E-COMMERCE --}}
            {{-- ============================================================ --}}
            <div class="dash-view hidden" id="view-ecom">

                {{-- Stats principales --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="ecom-stat-card">
                        <div class="icon" style="background:#dcfce7;color:#059669;">
                            <i class="ri-money-euro-circle-line"></i>
                        </div>
                        <div>
                            <div class="value">{{ number_format($totalVentes, 0, ',', ' ') }}</div>
                            <div class="label">Ventes (FCFA)</div>
                        </div>
                    </div>
                    <div class="ecom-stat-card">
                        <div class="icon" style="background:#dbeafe;color:#005792;">
                            <i class="ri-truck-line"></i>
                        </div>
                        <div>
                            <div class="value">{{ number_format($totalLivraison, 0, ',', ' ') }}</div>
                            <div class="label">Livraisons (FCFA)</div>
                        </div>
                    </div>
                    <div class="ecom-stat-card">
                        <div class="icon" style="background:#fef3c7;color:#d97706;">
                            <i class="ri-shopping-bag-3-line"></i>
                        </div>
                        <div>
                            <div class="value">{{ $nbAchatsTotal }}</div>
                            <div class="label">Articles vendus</div>
                        </div>
                    </div>
                    <div class="ecom-stat-card">
                        <div class="icon" style="background:#fce7f3;color:#ec4899;">
                            <i class="ri-store-2-line"></i>
                        </div>
                        <div>
                            <div class="value">{{ $nbProduits }}</div>
                            <div class="label">Produits actifs</div>
                        </div>
                    </div>
                </div>

                {{-- Statuts des commandes --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
                    <div class="ecom-stat-card">
                        <div class="icon" style="background:#fef3c7;color:#d97706;">
                            <i class="ri-time-line"></i>
                        </div>
                        <div>
                            <div class="value">{{ $countEncours }}</div>
                            <div class="label">Commandes en cours</div>
                        </div>
                    </div>
                    <div class="ecom-stat-card">
                        <div class="icon" style="background:#dbeafe;color:#005792;">
                            <i class="ri-checkbox-circle-line"></i>
                        </div>
                        <div>
                            <div class="value">{{ $countConfirmer }}</div>
                            <div class="label">Confirmées</div>
                        </div>
                    </div>
                    <div class="ecom-stat-card">
                        <div class="icon" style="background:#dcfce7;color:#059669;">
                            <i class="ri-truck-line"></i>
                        </div>
                        <div>
                            <div class="value">{{ $countLivree }}</div>
                            <div class="label">Livrées</div>
                        </div>
                    </div>
                </div>

                {{-- Chart ventes mensuelles --}}
                <div class="bg-white rounded-2xl p-5 border border-gray-200 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 text-base">Ventes mensuelles</h3>
                            <span class="text-xs text-gray-500">Année {{ \Carbon\Carbon::now()->year }}</span>
                        </div>
                    </div>
                    <div id="ecom-chart"></div>
                </div>

                {{-- Commandes récentes --}}
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900">Commandes récentes</h3>
                    </div>
                    @if($commandesRecentes->isEmpty())
                        <p class="p-6 text-center text-gray-400">Aucune commande.</p>
                    @else
                        <table class="w-full">
                            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">Code</th>
                                    <th class="px-4 py-3 text-left">Client</th>
                                    <th class="px-4 py-3 text-left">Montant</th>
                                    <th class="px-4 py-3 text-left">Statut</th>
                                    <th class="px-4 py-3 text-left">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($commandesRecentes as $c)
                                    <tr class="border-t border-gray-100">
                                        <td class="px-4 py-3 text-sm font-mono text-gray-800">{{ $c['code'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $c['client'] }}</td>
                                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                            {{ number_format($c['montant'], 0, ',', ' ') }} F
                                        </td>
                                        <td class="px-4 py-3">
                                            @php
                                                $badgeClass = match($c['status']) {
                                                    'encours' => 'bg-yellow-100 text-yellow-700',
                                                    'confirmer' => 'bg-blue-100 text-blue-700',
                                                    'livrée' => 'bg-green-100 text-green-700',
                                                    default => 'bg-gray-100 text-gray-700',
                                                };
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                                {{ ucfirst($c['status']) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $c['date'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

            </div> {{-- END #view-ecom --}}
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

    {{-- Toggle Supriz / E-commerce + chart e-commerce --}}
    <script>
        (function () {
            const toggle = document.querySelector('.dashboard-toggle');
            const vSupriz = document.getElementById('view-supriz');
            const vEcom = document.getElementById('view-ecom');
            const ecomData = @json($ecomActivity ?? ['months' => [], 'totals' => []]);
            let ecomChart = null;

            function setView(view) {
                toggle.dataset.active = view;
                if (view === 'supriz') {
                    vEcom.classList.add('hidden');
                    vSupriz.classList.remove('hidden');
                } else {
                    vSupriz.classList.add('hidden');
                    vEcom.classList.remove('hidden');
                    // Init chart e-commerce à la première ouverture
                    if (!ecomChart && document.getElementById('ecom-chart')) {
                        ecomChart = new ApexCharts(document.getElementById('ecom-chart'), {
                            chart: {
                                type: 'area', height: 300,
                                fontFamily: 'Inter, sans-serif',
                                toolbar: { show: false },
                            },
                            series: [{
                                name: 'Ventes (FCFA)',
                                data: ecomData.totals,
                            }],
                            xaxis: {
                                categories: ecomData.months,
                                labels: { style: { colors: '#6b7280', fontSize: '11px' } },
                                axisBorder: { show: false },
                                axisTicks: { show: false },
                            },
                            yaxis: {
                                labels: {
                                    style: { colors: '#6b7280', fontSize: '11px' },
                                    formatter: v => Math.round(v).toLocaleString('fr-FR'),
                                },
                            },
                            colors: ['#005792'],
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shadeIntensity: 1,
                                    opacityFrom: 0.4,
                                    opacityTo: 0.05,
                                    stops: [0, 100],
                                },
                            },
                            stroke: { curve: 'smooth', width: 3 },
                            dataLabels: { enabled: false },
                            grid: { borderColor: '#f3f4f6', strokeDashArray: 4 },
                            tooltip: {
                                y: { formatter: v => Math.round(v).toLocaleString('fr-FR') + ' F' },
                            },
                        });
                        ecomChart.render();
                    }
                }
            }

            toggle.querySelectorAll('.toggle-opt').forEach(btn => {
                btn.addEventListener('click', () => setView(btn.dataset.view));
            });
        })();
    </script>

</body>

</html>
