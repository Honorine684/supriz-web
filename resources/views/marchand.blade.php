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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <title>Supriz - Marchands</title>
</head>

<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">
    @include('partials.gtm-body')

    @include('components.sidebar')

    <main class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20">

        <div class="pt-6 pb-0 pl-6 pr-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex items-center justify-between mb-5">
                <img src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="">
                <h4 class="font-bold text-right text-white">Marchands</h4>
                <div class="flex">
                    <img class="mr-3" src="{{ asset('/images/svg/menu-alt_svgrepo.com.svg') }}" alt="">
                </div>
            </div>
        </div>

        <div class="p-6">

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

            <form action="{{ isset($marchand) ? route('admin.marchand.update', $marchand->id) : route('admin.marchand.store') }}"
                  method="POST"
                  class="bg-white p-4 rounded-20 shadow mb-6">
                @csrf
                @if (isset($marchand))
                    @method('PUT')
                @endif

                <h3 class="font-semibold mb-4">{{ isset($marchand) ? 'Modifier le marchand' : 'Ajouter un marchand' }}</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-sm">Nom *</label>
                        <input type="text" name="nom" value="{{ old('nom', $marchand->nom ?? '') }}"
                               class="w-full p-2 border rounded" required>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm">Téléphone <span class="text-xs text-gray-500">(interne, non exposé aux clients)</span></label>
                        <input type="text" name="telephone" value="{{ old('telephone', $marchand->telephone ?? '') }}"
                               class="w-full p-2 border rounded">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm">Ville</label>
                        <input type="text" name="ville" value="{{ old('ville', $marchand->ville ?? '') }}"
                               class="w-full p-2 border rounded">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm">Adresse</label>
                        <input type="text" name="adresse" value="{{ old('adresse', $marchand->adresse ?? '') }}"
                               class="w-full p-2 border rounded">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm">Latitude</label>
                        <input type="number" step="0.0000001" name="latitude" id="latitude"
                               value="{{ old('latitude', $marchand->latitude ?? '') }}"
                               class="w-full p-2 border rounded" placeholder="ex : 6.3702928">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm">Longitude</label>
                        <input type="number" step="0.0000001" name="longitude" id="longitude"
                               value="{{ old('longitude', $marchand->longitude ?? '') }}"
                               class="w-full p-2 border rounded" placeholder="ex : 2.3912362">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block mb-1 text-sm">Rechercher l'adresse sur la carte</label>
                    <div class="relative">
                        <input type="text" id="map-search"
                               class="w-full p-2 border rounded"
                               placeholder="Ex : Carrefour Cadjehoun, Cotonou"
                               autocomplete="off">
                        <ul id="map-results"
                            class="absolute z-[1000] hidden w-full max-h-60 overflow-y-auto bg-white border border-gray-200 rounded shadow"></ul>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Cliquez sur un résultat, ou déplacez directement le marqueur sur la carte pour ajuster.
                    </p>
                    <div id="marchand-map" class="mt-2 rounded border" style="height: 360px; z-index: 0;"></div>
                </div>

                <div class="flex gap-2 mt-4">
                    <input type="submit"
                           class="p-2 text-white rounded cursor-pointer btn-design flex-1"
                           value="{{ isset($marchand) ? 'Modifier' : 'Enregistrer' }}">
                    @if (isset($marchand))
                        <a href="{{ route('admin.marchand') }}"
                           class="p-2 text-center bg-gray-300 text-gray-800 rounded flex-1">
                            Annuler
                        </a>
                    @endif
                </div>
            </form>

            <p class="mt-3 mb-2">Liste des marchands</p>

            <div class="p-3 overflow-x-auto bg-white border border-gray-100 shadow-md rounded-20 shadow-black/5">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 border-b">
                            <th class="px-4 py-2">Nom</th>
                            <th class="px-4 py-2">Ville</th>
                            <th class="px-4 py-2">Adresse</th>
                            <th class="px-4 py-2">Coords (lat, lng)</th>
                            <th class="px-4 py-2">Téléphone</th>
                            <th class="px-4 py-2">Produits</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($marchands as $m)
                            <tr>
                                <td class="px-4 py-2 border-b border-b-gray-50">
                                    <span class="text-[13px] font-medium text-gray-700">{{ $m->nom }}</span>
                                </td>
                                <td class="px-4 py-2 border-b border-b-gray-50">
                                    <span class="text-[13px] text-gray-500">{{ $m->ville ?? '—' }}</span>
                                </td>
                                <td class="px-4 py-2 border-b border-b-gray-50">
                                    <span class="text-[13px] text-gray-500">{{ $m->adresse ?? '—' }}</span>
                                </td>
                                <td class="px-4 py-2 border-b border-b-gray-50">
                                    <span class="text-[13px] text-gray-500">
                                        @if ($m->latitude && $m->longitude)
                                            {{ $m->latitude }}, {{ $m->longitude }}
                                        @else
                                            <span class="text-red-500">manquant</span>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-4 py-2 border-b border-b-gray-50">
                                    <span class="text-[13px] text-gray-500">{{ $m->telephone ?? '—' }}</span>
                                </td>
                                <td class="px-4 py-2 border-b border-b-gray-50">
                                    <span class="text-[13px] text-gray-500">{{ $m->products()->count() }}</span>
                                </td>
                                <td class="px-4 py-2 border-b border-b-gray-50">
                                    <div class="flex">
                                        <a href="{{ route('admin.marchand.edit', $m->id) }}"
                                           class="mr-2 text-[13px] text-gray-400 cursor-pointer">
                                            <img class="relative top-1"
                                                 src="{{ asset('/images/svg/edit-3_svgrepo.com.svg') }}" alt="Modifier">
                                        </a>
                                        <form action="{{ route('admin.marchand.destroy', $m->id) }}"
                                              method="POST" class="text-[13px] text-gray-400">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Supprimer ce marchand ?')">
                                                <img class="relative top-1"
                                                     src="{{ asset('/images/svg/delete-left_svgrepo.com.svg') }}"
                                                     alt="Supprimer">
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-400 text-sm">
                                    Aucun marchand pour le moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="js/chart.js"></script>
    <script src="{{ asset('js/imagefile.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        (function () {
            const COTONOU = [6.3702928, 2.3912362];
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const searchInput = document.getElementById('map-search');
            const resultsList = document.getElementById('map-results');

            const initialLat = parseFloat(latInput.value);
            const initialLng = parseFloat(lngInput.value);
            const hasInitial = !isNaN(initialLat) && !isNaN(initialLng);
            const center = hasInitial ? [initialLat, initialLng] : COTONOU;

            const map = L.map('marchand-map').setView(center, hasInitial ? 16 : 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            let marker = null;
            function setMarker(lat, lng) {
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                    marker.on('dragend', function (e) {
                        const pos = e.target.getLatLng();
                        latInput.value = pos.lat.toFixed(7);
                        lngInput.value = pos.lng.toFixed(7);
                    });
                }
                latInput.value = lat.toFixed(7);
                lngInput.value = lng.toFixed(7);
            }

            if (hasInitial) {
                setMarker(initialLat, initialLng);
            }

            // Cliquer sur la carte pose / déplace le marqueur
            map.on('click', function (e) {
                setMarker(e.latlng.lat, e.latlng.lng);
            });

            // Recherche Nominatim (debounce 600 ms, min 3 caractères, 1 req/s policy)
            let debounceTimer = null;
            function hideResults() {
                resultsList.classList.add('hidden');
                resultsList.innerHTML = '';
            }

            searchInput.addEventListener('input', function () {
                const q = this.value.trim();
                clearTimeout(debounceTimer);
                if (q.length < 3) {
                    hideResults();
                    return;
                }
                debounceTimer = setTimeout(function () {
                    const url = 'https://nominatim.openstreetmap.org/search'
                        + '?format=json&addressdetails=1&limit=5'
                        + '&countrycodes=bj'
                        + '&q=' + encodeURIComponent(q);
                    fetch(url, { headers: { 'Accept-Language': 'fr' } })
                        .then(r => r.json())
                        .then(items => {
                            resultsList.innerHTML = '';
                            if (!items.length) {
                                resultsList.innerHTML = '<li class="p-2 text-sm text-gray-500">Aucun résultat</li>';
                                resultsList.classList.remove('hidden');
                                return;
                            }
                            items.forEach(item => {
                                const li = document.createElement('li');
                                li.className = 'p-2 text-sm cursor-pointer hover:bg-gray-100 border-b last:border-b-0';
                                li.textContent = item.display_name;
                                li.addEventListener('click', function () {
                                    const lat = parseFloat(item.lat);
                                    const lng = parseFloat(item.lon);
                                    map.setView([lat, lng], 17);
                                    setMarker(lat, lng);
                                    searchInput.value = item.display_name;
                                    hideResults();
                                });
                                resultsList.appendChild(li);
                            });
                            resultsList.classList.remove('hidden');
                        })
                        .catch(() => hideResults());
                }, 600);
            });

            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !resultsList.contains(e.target)) {
                    hideResults();
                }
            });
        })();
    </script>

</body>

</html>
