<!DOCTYPE html>
<html lang="fr">

<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href={{ asset('css/main.css') }}>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Supriz - Badges</title>
</head>

<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">
    @include('partials.gtm-body')

    @include('components.sidebar')

    <main class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20 ">

        <div class="pt-4 pb-0 px-4 md:pt-6 md:px-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <img class="max-w-full h-auto" src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="">
                <h4 class="font-bold text-right text-white text-base md:text-lg">Badges</h4>
                <div class="flex">
                    <img class="mr-3 max-w-full h-auto" src="{{ asset('/images/svg/menu-alt_svgrepo.com.svg') }}" alt="">
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-500 text-center p-2 rounded text-white mb-3">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-500 text-center p-2 rounded text-white mb-3">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500 text-center p-2 rounded text-white mb-3">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="px-4 md:px-6">
            <form action="{{ isset($badge) ? route('admin.badges.update', $badge->id) : route('admin.badges.store') }}"
                method="POST" class="p-4 mb-6 bg-white shadow-md rounded-20">
                @csrf
                @if(isset($badge))
                    @method('PUT')
                @endif

                <p class="mb-3 font-semibold text-gray-700">
                    {{ isset($badge) ? 'Modifier le badge' : 'Créer un badge' }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
                    <div class="md:col-span-3">
                        <label class="block mb-2 text-sm text-gray-600">Nom du badge</label>
                        <input type="text" name="name"
                               value="{{ old('name', $badge->name ?? '') }}"
                               placeholder="Ex: Anniversaire, Mariage..."
                               maxlength="50"
                               class="w-full p-2 border border-gray-300 rounded" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm text-gray-600">Couleur (optionnel)</label>
                        <input type="color" name="color"
                               value="{{ old('color', $badge->color ?? '#005792') }}"
                               class="w-full h-10 p-1 border border-gray-300 rounded cursor-pointer">
                    </div>
                </div>

                {{-- Icon picker --}}
                @php
                    // Liste curée d'icônes cross-plateforme (clé = valeur stockée, ri = classe web)
                    $iconChoices = [
                        'cake' => 'ri-cake-3-line',
                        'heart' => 'ri-heart-line',
                        'gift' => 'ri-gift-line',
                        'star' => 'ri-star-line',
                        'flower' => 'ri-flower-line',
                        'diamond' => 'ri-vip-diamond-line',
                        'crown' => 'ri-vip-crown-line',
                        'fire' => 'ri-fire-line',
                        'trophy' => 'ri-trophy-line',
                        'magic' => 'ri-magic-line',
                        'sparkle' => 'ri-sparkling-line',
                        'medal' => 'ri-medal-line',
                        'chat' => 'ri-chat-heart-line',
                        'balloon' => 'ri-service-line',
                        'music' => 'ri-music-2-line',
                        'party' => 'ri-cake-2-line',
                        'lightning' => 'ri-flashlight-line',
                        'sun' => 'ri-sun-line',
                        'moon' => 'ri-moon-line',
                        'baby' => 'ri-parent-line',
                        'ring' => 'ri-focus-line',
                        'briefcase' => 'ri-briefcase-line',
                        'graduation' => 'ri-school-line',
                        'palette' => 'ri-palette-line',
                        'coffee' => 'ri-cup-line',
                        'plane' => 'ri-plane-line',
                        'car' => 'ri-car-line',
                        'home' => 'ri-home-heart-line',
                        'shield' => 'ri-shield-star-line',
                        'thumb' => 'ri-thumb-up-line',
                    ];
                    $currentIcon = old('icon', $badge->icon ?? '');
                @endphp

                <div class="mb-3">
                    <span class="mb-2 text-sm text-gray-600 block">Icône (optionnel)</span>
                    <input type="hidden" name="icon" id="icon-input" value="{{ $currentIcon }}">
                    <div class="grid grid-cols-6 md:grid-cols-10 gap-2 p-3 bg-gray-50 border border-gray-200 rounded">
                        <button type="button" data-icon=""
                                class="icon-choice aspect-square flex items-center justify-center text-xs text-gray-500 border-2 rounded-lg transition-all {{ $currentIcon === '' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white hover:border-gray-400' }}">
                            <i class="ri-forbid-line"></i>
                        </button>
                        @foreach ($iconChoices as $key => $riClass)
                            <button type="button" data-icon="{{ $key }}"
                                    class="icon-choice aspect-square flex items-center justify-center text-lg border-2 rounded-lg transition-all {{ $currentIcon === $key ? 'border-blue-500 bg-blue-50 text-blue-600' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-400' }}">
                                <i class="{{ $riClass }}"></i>
                            </button>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        L'icône choisie s'affichera devant le nom du badge dans l'app.
                    </p>
                </div>

                <script>
                    (function () {
                        const input = document.getElementById('icon-input');
                        document.querySelectorAll('.icon-choice').forEach(btn => {
                            btn.addEventListener('click', () => {
                                document.querySelectorAll('.icon-choice').forEach(b => {
                                    b.classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-600');
                                    b.classList.add('border-gray-200', 'bg-white');
                                    if (b.dataset.icon !== '') b.classList.add('text-gray-600');
                                });
                                btn.classList.remove('border-gray-200', 'bg-white', 'text-gray-600');
                                btn.classList.add('border-blue-500', 'bg-blue-50');
                                if (btn.dataset.icon !== '') btn.classList.add('text-blue-600');
                                input.value = btn.dataset.icon;
                            });
                        });
                    })();
                </script>

                <div class="flex flex-wrap gap-2">
                    <input type="submit"
                           class="px-4 py-2 text-white rounded cursor-pointer btn-design"
                           value="{{ isset($badge) ? 'Modifier' : 'Enregistrer' }}">

                    @if(isset($badge))
                        <a href="{{ route('admin.badges') }}"
                           class="px-4 py-2 text-gray-600 bg-gray-200 rounded hover:bg-gray-300">
                            Annuler
                        </a>
                    @endif
                </div>
            </form>

            <p class="mt-3 mb-2 font-semibold text-gray-700">Liste des badges ({{ $badges->count() }})</p>

            <div class="p-3 overflow-x-auto bg-white border border-gray-100 shadow-md rounded-20 shadow-black/5">
                @if($badges->isEmpty())
                    <p class="p-6 text-center text-gray-400">Aucun badge enregistré.</p>
                @else
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-gray-500 text-[12px] uppercase">
                                <th class="px-4 py-2">Aperçu</th>
                                <th class="px-4 py-2">Nom</th>
                                <th class="px-4 py-2">Couleur</th>
                                <th class="px-4 py-2">Créé le</th>
                                <th class="px-4 py-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $iconMap = [
                                    'cake' => 'ri-cake-3-line','heart' => 'ri-heart-line','gift' => 'ri-gift-line',
                                    'star' => 'ri-star-line','flower' => 'ri-flower-line','diamond' => 'ri-vip-diamond-line',
                                    'crown' => 'ri-vip-crown-line','fire' => 'ri-fire-line','trophy' => 'ri-trophy-line',
                                    'magic' => 'ri-magic-line','sparkle' => 'ri-sparkling-line','medal' => 'ri-medal-line',
                                    'chat' => 'ri-chat-heart-line','balloon' => 'ri-service-line','music' => 'ri-music-2-line',
                                    'party' => 'ri-cake-2-line','lightning' => 'ri-flashlight-line','sun' => 'ri-sun-line',
                                    'moon' => 'ri-moon-line','baby' => 'ri-parent-line','ring' => 'ri-focus-line',
                                    'briefcase' => 'ri-briefcase-line','graduation' => 'ri-school-line','palette' => 'ri-palette-line',
                                    'coffee' => 'ri-cup-line','plane' => 'ri-plane-line','car' => 'ri-car-line',
                                    'home' => 'ri-home-heart-line','shield' => 'ri-shield-star-line','thumb' => 'ri-thumb-up-line',
                                ];
                            @endphp
                            @foreach ($badges as $b)
                                @php
                                    $c = $b->color ?? '#005792';
                                    $riClass = $iconMap[$b->icon] ?? null;
                                @endphp
                                <tr>
                                    <td class="px-4 py-2 border-b border-b-gray-50">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full"
                                              style="color: {{ $c }}; background-color: {{ $c }}1F; border: 1px solid {{ $c }}40;">
                                            @if ($riClass)
                                                <i class="{{ $riClass }}"></i>
                                            @endif
                                            {{ $b->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-[13px] text-gray-700 border-b border-b-gray-50">
                                        {{ $b->name }}
                                    </td>
                                    <td class="px-4 py-2 border-b border-b-gray-50">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-block w-5 h-5 border border-gray-200 rounded"
                                                  style="background-color: {{ $c }};"></span>
                                            <code class="text-xs text-gray-500">{{ $c }}</code>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 text-[13px] text-gray-400 border-b border-b-gray-50">
                                        {{ $b->created_at?->format('d-m-Y') }}
                                    </td>
                                    <td class="px-4 py-2 text-right border-b border-b-gray-50">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.badges.edit', $b->id) }}"
                                               class="text-blue-600 hover:underline text-sm">Modifier</a>
                                            <form action="{{ route('admin.badges.destroy', $b->id) }}"
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Supprimer ce badge ?')"
                                                        class="text-red-600 hover:underline text-sm">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
