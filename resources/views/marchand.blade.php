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
    <title>Supriz - Marchands</title>
</head>

<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">
    @include('partials.gtm-body')

    @include('components.sidebar')

    <main class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20">

        <div class="pt-4 md:pt-6 pb-0 px-4 md:px-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex items-center justify-between gap-3 flex-wrap mb-5">
                <img src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="" class="max-w-[120px] md:max-w-none h-auto">
                <h4 class="font-bold text-right text-white text-sm md:text-base">Marchands</h4>
                <div class="flex">
                    <img class="mr-3" src="{{ asset('/images/svg/menu-alt_svgrepo.com.svg') }}" alt="">
                </div>
            </div>
        </div>

        <div class="p-4 md:p-6">

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
                    <div id="marchand-map" class="mt-2 rounded border h-[260px] md:h-[360px]" style="z-index: 0;"></div>
                </div>

                {{-- Jours travaillés --}}
                @php
                    $days = [
                        'mon' => 'Lun', 'tue' => 'Mar', 'wed' => 'Mer',
                        'thu' => 'Jeu', 'fri' => 'Ven', 'sat' => 'Sam', 'sun' => 'Dim',
                    ];
                    $selectedDays = old('working_days', $marchand->working_days ?? ['mon','tue','wed','thu','fri']);
                    $hours = old('working_hours', $marchand->working_hours ?? [['open' => '09:00', 'close' => '18:00']]);
                @endphp

                <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <h4 class="font-semibold text-gray-700 mb-3">Jours d'ouverture</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($days as $code => $label)
                            @php $checked = in_array($code, $selectedDays); @endphp
                            <label class="cursor-pointer">
                                <input type="checkbox" name="working_days[]" value="{{ $code }}"
                                       class="hidden peer" {{ $checked ? 'checked' : '' }}>
                                <span class="inline-block px-4 py-2 text-sm font-medium border-2 border-gray-300 rounded-full text-gray-600 transition-all peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:border-blue-400">
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Plages horaires --}}
                <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold text-gray-700">Plages horaires</h4>
                        <button type="button" id="add-range"
                                class="px-3 py-1 text-xs text-white bg-blue-600 rounded hover:bg-blue-700">
                            + Ajouter une plage
                        </button>
                    </div>
                    <div id="ranges-list" class="space-y-2">
                        @foreach ($hours as $i => $r)
                            <div class="flex flex-wrap items-center gap-2 range-row">
                                <span class="text-sm text-gray-500 w-16">Plage {{ $i + 1 }}</span>
                                <input type="time" name="working_hours[{{ $i }}][open]"
                                       value="{{ $r['open'] ?? '09:00' }}"
                                       class="p-2 border border-gray-300 rounded min-w-0 flex-shrink">
                                <span class="text-gray-400">à</span>
                                <input type="time" name="working_hours[{{ $i }}][close]"
                                       value="{{ $r['close'] ?? '18:00' }}"
                                       class="p-2 border border-gray-300 rounded min-w-0 flex-shrink">
                                <button type="button" class="text-red-500 hover:underline text-sm remove-range">
                                    Retirer
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Ex: 09:00–13:00 puis 15:00–19:00</p>
                </div>

                <script>
                    (function () {
                        const list = document.getElementById('ranges-list');
                        const addBtn = document.getElementById('add-range');
                        if (!list || !addBtn) return;

                        function nextIndex() {
                            return list.querySelectorAll('.range-row').length;
                        }

                        function renumber() {
                            list.querySelectorAll('.range-row').forEach((row, i) => {
                                row.querySelector('span').textContent = 'Plage ' + (i + 1);
                                row.querySelectorAll('input[type=time]').forEach((inp) => {
                                    inp.name = inp.name.replace(/working_hours\[\d+\]/, 'working_hours[' + i + ']');
                                });
                            });
                        }

                        function attachRemove(btn) {
                            btn.addEventListener('click', function () {
                                btn.closest('.range-row').remove();
                                renumber();
                            });
                        }

                        list.querySelectorAll('.remove-range').forEach(attachRemove);

                        addBtn.addEventListener('click', function () {
                            const i = nextIndex();
                            const row = document.createElement('div');
                            row.className = 'flex flex-wrap items-center gap-2 range-row';
                            row.innerHTML =
                                '<span class="text-sm text-gray-500 w-16">Plage ' + (i + 1) + '</span>' +
                                '<input type="time" name="working_hours[' + i + '][open]" value="09:00" class="p-2 border border-gray-300 rounded min-w-0 flex-shrink">' +
                                '<span class="text-gray-400">à</span>' +
                                '<input type="time" name="working_hours[' + i + '][close]" value="18:00" class="p-2 border border-gray-300 rounded min-w-0 flex-shrink">' +
                                '<button type="button" class="text-red-500 hover:underline text-sm remove-range">Retirer</button>';
                            list.appendChild(row);
                            attachRemove(row.querySelector('.remove-range'));
                        });
                    })();
                </script>

                <div class="flex flex-col sm:flex-row gap-2 mt-4">
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

            @php
                // Normalise un numéro pour WhatsApp (international sans +, ni espace).
                // Bénin : 10 chiffres commençant par 0 -> 229 + 9 restants ; 8 chiffres -> 229 + ...
                if (!function_exists('supriz_wa_number')) {
                    function supriz_wa_number($num) {
                        if (!$num) return null;
                        $s = preg_replace('/[^\d+]/', '', (string) $num);
                        if ($s === '') return null;
                        if (str_starts_with($s, '+')) $s = substr($s, 1);
                        if (strlen($s) === 10 && str_starts_with($s, '0')) {
                            $s = '229' . substr($s, 1);
                        } elseif (strlen($s) === 8) {
                            $s = '229' . $s;
                        }
                        return $s;
                    }
                }
            @endphp

            <p class="mt-3 mb-2">Liste des marchands</p>

            <div class="p-3 overflow-x-auto bg-white border border-gray-100 shadow-md rounded-20 shadow-black/5">
                <table class="w-full min-w-[720px]">
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
                                    @php
                                        $mWa = supriz_wa_number($m->telephone);
                                        $waText = rawurlencode("Bonjour {$m->nom}, je vous contacte de la part de Supriz.");
                                    @endphp
                                    @if ($mWa)
                                        <a href="https://wa.me/{{ $mWa }}?text={{ $waText }}" target="_blank"
                                           class="text-[13px] text-green-600 hover:text-green-700 hover:underline inline-flex items-center gap-1">
                                            <i class="ri-whatsapp-line"></i> {{ $m->telephone }}
                                        </a>
                                    @else
                                        <span class="text-[13px] text-gray-500">{{ $m->telephone ?? '—' }}</span>
                                    @endif
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

    {{-- Google Maps JavaScript API (Demo Key) + Places library --}}
    <script>
        function initMarchandMap() {
            const COTONOU = { lat: 6.3702928, lng: 2.3912362 };
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const searchInput = document.getElementById('map-search');
            const resultsList = document.getElementById('map-results');
            const adresseInput = document.querySelector('input[name="adresse"]');
            const villeInput = document.querySelector('input[name="ville"]');

            const initialLat = parseFloat(latInput.value);
            const initialLng = parseFloat(lngInput.value);
            const hasInitial = !isNaN(initialLat) && !isNaN(initialLng);
            const center = hasInitial ? { lat: initialLat, lng: initialLng } : COTONOU;

            const map = new google.maps.Map(document.getElementById('marchand-map'), {
                center: center,
                zoom: hasInitial ? 16 : 12,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
            });

            let marker = null;
            function setMarker(lat, lng) {
                const pos = { lat: lat, lng: lng };
                if (marker) {
                    marker.setPosition(pos);
                } else {
                    marker = new google.maps.Marker({
                        position: pos,
                        map: map,
                        draggable: true,
                        animation: google.maps.Animation.DROP,
                    });
                    marker.addListener('dragend', function (e) {
                        latInput.value = e.latLng.lat().toFixed(7);
                        lngInput.value = e.latLng.lng().toFixed(7);
                    });
                }
                latInput.value = lat.toFixed(7);
                lngInput.value = lng.toFixed(7);
            }

            // Reverse geocoding des coordonnées → pré-remplit Adresse + Ville
            // (uniquement les champs vides). Utilisé à l'ouverture de « Modifier ».
            function fillAddressFromCoords(lat, lng) {
                if (!adresseInput && !villeInput) return;
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ location: { lat: lat, lng: lng } }, function (results, status) {
                    if (status !== 'OK' || !results || !results.length) return;
                    const result = results[0];
                    let formatted = (result.formatted_address || '')
                        .replace(/^[A-Z0-9]{4,8}\+[A-Z0-9]{2,3},?\s*/, '')
                        .trim();
                    if (adresseInput && !adresseInput.value.trim() && formatted) {
                        adresseInput.value = formatted;
                    }
                    if (villeInput && !villeInput.value.trim() && result.address_components) {
                        const city = result.address_components.find(function (c) {
                            return c.types.indexOf('locality') !== -1
                                || c.types.indexOf('administrative_area_level_2') !== -1;
                        });
                        if (city) villeInput.value = city.long_name;
                    }
                });
            }

            if (hasInitial) {
                setMarker(initialLat, initialLng);
                // Auto-remplissage à l'ouverture de « Modifier » : reverse
                // geocoding des coordonnées existantes.
                fillAddressFromCoords(initialLat, initialLng);
            }

            // Click sur la carte pose / déplace le marqueur
            map.addListener('click', function (e) {
                setMarker(e.latLng.lat(), e.latLng.lng());
            });

            // --- Autocomplete Places (filtré Bénin) ---
            const sessionToken = new google.maps.places.AutocompleteSessionToken();
            const autocompleteService = new google.maps.places.AutocompleteService();
            const placesService = new google.maps.places.PlacesService(map);

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
                    autocompleteService.getPlacePredictions({
                        input: q,
                        componentRestrictions: { country: 'bj' },
                        sessionToken: sessionToken,
                    }, function (predictions, status) {
                        resultsList.innerHTML = '';
                        if (status !== google.maps.places.PlacesServiceStatus.OK || !predictions || !predictions.length) {
                            resultsList.innerHTML = '<li class="p-2 text-sm text-gray-500">Aucun résultat</li>';
                            resultsList.classList.remove('hidden');
                            return;
                        }
                        predictions.forEach(function (pred) {
                            const li = document.createElement('li');
                            li.className = 'p-2 text-sm cursor-pointer hover:bg-gray-100 border-b last:border-b-0';
                            const main = pred.structured_formatting ? pred.structured_formatting.main_text : pred.description;
                            const sec = pred.structured_formatting && pred.structured_formatting.secondary_text
                                ? pred.structured_formatting.secondary_text : '';
                            li.innerHTML = '<div class="font-medium text-gray-800">' + main + '</div>'
                                + (sec ? '<div class="text-xs text-gray-500">' + sec + '</div>' : '');
                            li.addEventListener('click', function () {
                                placesService.getDetails({
                                    placeId: pred.place_id,
                                    fields: ['geometry', 'formatted_address', 'address_components'],
                                    sessionToken: sessionToken,
                                }, function (place, st) {
                                    if (st === google.maps.places.PlacesServiceStatus.OK && place.geometry) {
                                        const lat = place.geometry.location.lat();
                                        const lng = place.geometry.location.lng();
                                        map.setCenter({ lat: lat, lng: lng });
                                        map.setZoom(17);
                                        setMarker(lat, lng);
                                        searchInput.value = place.formatted_address || pred.description;

                                        // Remplit le champ « Adresse » (localisation du
                                        // marchand) pour que l'app n'affiche plus le nom à la
                                        // place du point de retrait.
                                        if (adresseInput && place.formatted_address) {
                                            adresseInput.value = place.formatted_address;
                                        }
                                        // Extrait la ville depuis les composants d'adresse.
                                        if (villeInput && place.address_components) {
                                            const city = place.address_components.find(function (c) {
                                                return c.types.indexOf('locality') !== -1
                                                    || c.types.indexOf('administrative_area_level_2') !== -1;
                                            });
                                            if (city) villeInput.value = city.long_name;
                                        }
                                    }
                                    hideResults();
                                });
                            });
                            resultsList.appendChild(li);
                        });
                        resultsList.classList.remove('hidden');
                    });
                }, 400);
            });

            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !resultsList.contains(e.target)) {
                    hideResults();
                }
            });
        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&language=fr&region=BJ&callback=initMarchandMap">
    </script>

</body>

</html>
