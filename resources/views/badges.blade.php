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

        <div class="pt-6 pb-0 pl-6 pr-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex items-center justify-between mb-5">
                <img src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="">
                <h4 class="font-bold text-right text-white">Badges</h4>
                <div class="flex">
                    <img class="mr-3" src="{{ asset('/images/svg/menu-alt_svgrepo.com.svg') }}" alt="">
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

        <div class="px-6">
            <form action="{{ isset($badge) ? route('admin.badges.update', $badge->id) : route('admin.badges.store') }}"
                method="POST" class="p-4 mb-6 bg-white shadow-md rounded-20">
                @csrf
                @if(isset($badge))
                    @method('PUT')
                @endif

                <p class="mb-3 font-semibold text-gray-700">
                    {{ isset($badge) ? 'Modifier le badge' : 'Créer un badge' }}
                </p>

                <div class="flex gap-4 mb-3">
                    <div class="grid flex-1">
                        <span class="mb-2 text-sm text-gray-600">Nom du badge</span>
                        <input type="text" name="name"
                               value="{{ old('name', $badge->name ?? '') }}"
                               placeholder="Ex: Anniversaire, Mariage..."
                               maxlength="50"
                               class="p-2 border border-gray-300 rounded" required>
                    </div>

                    <div class="grid w-44">
                        <span class="mb-2 text-sm text-gray-600">Couleur (optionnel)</span>
                        <input type="color" name="color"
                               value="{{ old('color', $badge->color ?? '#005792') }}"
                               class="w-full h-10 p-1 border border-gray-300 rounded cursor-pointer">
                    </div>
                </div>

                <div class="flex gap-2">
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
                            @foreach ($badges as $b)
                                @php
                                    $c = $b->color ?? '#005792';
                                @endphp
                                <tr>
                                    <td class="px-4 py-2 border-b border-b-gray-50">
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full"
                                              style="color: {{ $c }}; background-color: {{ $c }}1F; border: 1px solid {{ $c }}40;">
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
