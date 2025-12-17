<!DOCTYPE html>
<html lang="fr">

<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href={{ asset('css/main.css') }}>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Supriz - Gestion des Avis</title>
    <style>
        .avis-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .avis-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .avis-card.hidden-avis {
            opacity: 0.6;
            background: #f9fafb;
        }
        .avis-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .avis-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .avis-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1B7B6A, #2da891);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }
        .avis-name {
            font-weight: 600;
            color: #1a1a1a;
            font-size: 16px;
        }
        .avis-date {
            font-size: 13px;
            color: #666;
        }
        .avis-stars {
            color: #ffc107;
            font-size: 16px;
        }
        .avis-message {
            color: #444;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .avis-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        .btn-toggle {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-toggle.visible {
            background: #d1fae5;
            color: #047857;
        }
        .btn-toggle.hidden {
            background: #fee2e2;
            color: #dc2626;
        }
        .btn-toggle:hover {
            transform: translateY(-1px);
        }
        .btn-delete {
            padding: 8px 16px;
            background: #f3f4f6;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            color: #666;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-delete:hover {
            background: #fee2e2;
            color: #dc2626;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1B7B6A;
        }
        .stat-label {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }
        .no-avis {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        .no-avis i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 15px;
        }
        .badge-visible {
            background: #d1fae5;
            color: #047857;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-hidden {
            background: #fee2e2;
            color: #dc2626;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
    </style>
</head>

<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">
    @include('partials.gtm-body')

    <!-- start: Sidebar -->
    @include('components.sidebar')
    <!-- end: Sidebar -->

    <!-- start: Main -->
    <main class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20">

        <div class="pt-6 pb-0 pl-6 pr-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex items-center justify-between mb-5">
                <img src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="">
                <h4 class="font-bold text-right text-white">Gestion des Avis</h4>
                <div class="flex">
                    <img class="mr-3" src="{{ asset('/images/svg/menu-alt_svgrepo.com.svg') }}" alt="">
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $avis->total() }}</div>
                    <div class="stat-label">Total avis</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $avis->where('visible', true)->count() }}</div>
                    <div class="stat-label">Visibles</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $avis->where('visible', false)->count() }}</div>
                    <div class="stat-label">En attente</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        @php
                            $avgRating = $avis->avg('note');
                        @endphp
                        {{ $avgRating ? number_format($avgRating, 1) : '-' }}
                    </div>
                    <div class="stat-label">Note moyenne</div>
                </div>
            </div>

            <!-- Liste des avis -->
            @if($avis->count() > 0)
                @foreach($avis as $item)
                    <div class="avis-card {{ !$item->visible ? 'hidden-avis' : '' }}" id="avis-{{ $item->id }}">
                        <div class="avis-header">
                            <div class="avis-author">
                                <div class="avis-avatar">{{ $item->initiales ?? strtoupper(substr($item->nom, 0, 2)) }}</div>
                                <div>
                                    <div class="avis-name">
                                        {{ $item->nom }}
                                        @if($item->visible)
                                            <span class="badge-visible">Visible</span>
                                        @else
                                            <span class="badge-hidden">Masqué</span>
                                        @endif
                                    </div>
                                    <div class="avis-date">{{ $item->created_at->format('d/m/Y à H:i') }}</div>
                                </div>
                            </div>
                            <div class="avis-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $item->note)
                                        <i class="bi bi-star-fill"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>

                        @if($item->message)
                            <div class="avis-message">
                                "{{ $item->message }}"
                            </div>
                        @else
                            <div class="avis-message" style="font-style: italic; color: #999;">
                                Aucun message
                            </div>
                        @endif

                        <div class="avis-actions">
                            <button class="btn-toggle {{ $item->visible ? 'visible' : 'hidden' }}"
                                    onclick="toggleVisibility({{ $item->id }})"
                                    id="btn-toggle-{{ $item->id }}">
                                @if($item->visible)
                                    <i class="bi bi-eye-fill"></i> Visible
                                @else
                                    <i class="bi bi-eye-slash-fill"></i> Masqué
                                @endif
                            </button>
                            <button class="btn-delete" onclick="deleteAvis({{ $item->id }})">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $avis->links() }}
                </div>
            @else
                <div class="no-avis">
                    <i class="bi bi-chat-square-text"></i>
                    <h3>Aucun avis pour le moment</h3>
                    <p>Les avis des utilisateurs apparaîtront ici.</p>
                </div>
            @endif
        </div>
    </main>

    <script>
        function toggleVisibility(id) {
            fetch(`/admin/avis/${id}/toggle-visibility`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const card = document.getElementById(`avis-${id}`);
                    const btn = document.getElementById(`btn-toggle-${id}`);
                    const badge = card.querySelector('.badge-visible, .badge-hidden');

                    if (data.visible) {
                        card.classList.remove('hidden-avis');
                        btn.classList.remove('hidden');
                        btn.classList.add('visible');
                        btn.innerHTML = '<i class="bi bi-eye-fill"></i> Visible';
                        badge.className = 'badge-visible';
                        badge.textContent = 'Visible';
                    } else {
                        card.classList.add('hidden-avis');
                        btn.classList.remove('visible');
                        btn.classList.add('hidden');
                        btn.innerHTML = '<i class="bi bi-eye-slash-fill"></i> Masqué';
                        badge.className = 'badge-hidden';
                        badge.textContent = 'Masqué';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue');
            });
        }

        function deleteAvis(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')) {
                return;
            }

            fetch(`/admin/avis/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const card = document.getElementById(`avis-${id}`);
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        card.remove();
                    }, 300);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue');
            });
        }
    </script>

    @vite('resources/js/app.js')
</body>
</html>
