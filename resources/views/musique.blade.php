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
    <title>Supriz - Musiques</title>
    <style>
        .audio-drop-zone {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background-color: #f9fafb;
        }
        .audio-drop-zone:hover {
            border-color: #005792;
            background-color: #E5F2FF;
        }
        .audio-drop-zone.dragover {
            border-color: #005792;
            background-color: #E5F2FF;
        }
        .music-card {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px;
            background: white;
            transition: all 0.3s;
            margin-bottom: 16px;
        }
        .music-card:hover {
            box-shadow: 0 4px 12px rgba(0, 87, 146, 0.1);
            border-color: #005792;
        }
        audio::-webkit-media-controls-panel {
            background-color: #f3f4f6;
        }
    </style>
</head>

<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">

    <!-- start: Sidebar -->
    @include('components.sidebar')
    <!-- end: Sidebar -->

    <!-- start: Main -->
    <main class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20">

        <div class="pt-6 pb-0 pl-6 pr-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex items-center justify-between mb-5">
                <img src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="">
                <h4 class="font-bold text-right text-white">Gestion des Musiques</h4>
                <div class="flex">
                    <img class="mr-3" src="{{ asset('/images/svg/menu-alt_svgrepo.com.svg') }}" alt="">
                    <div class="flex">
                        <input type="text" class="pr-10 rounded-20" placeholder="Recherche">
                        <img class="absolute cursor-pointer search_icon" src="{{ asset('/images/svg/search-alt-1_svgrepo.com.svg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-3 mb-4 text-center text-white bg-red-500 rounded-lg">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="p-3 mb-4 text-center text-white bg-green-500 rounded-lg">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 mb-4 text-center text-white bg-red-500 rounded-lg">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Formulaire d'ajout de musique -->
        <div class="p-8 mb-6 bg-white rounded-20" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <div class="flex items-center mb-6">
                <i class="mr-3 text-3xl ri-music-2-fill" style="color: #005792;"></i>
                <h2 class="text-2xl font-bold" style="color: #005792;">Ajouter une musique</h2>
            </div>

            <div class="p-4 mb-6 rounded-lg" style="background-color: #E5F2FF; border-left: 4px solid #005792;">
                <div class="flex items-start">
                    <i class="mt-1 mr-3 text-xl ri-information-line" style="color: #005792;"></i>
                    <div>
                        <p class="font-semibold" style="color: #005792;">📌 Instructions :</p>
                        <ul class="mt-2 ml-4 text-sm list-disc" style="color: #005792;">
                            <li>Formats audio acceptés : MP3, WAV, OGG</li>
                            <li>Durée maximale : 30 secondes</li>
                            <li>Taille audio maximale : 5 Mo</li>
                            <li>Chaque musique doit être associée à une catégorie Supriz</li>
                        </ul>
                    </div>
                </div>
            </div>

            <form action="{{ route('musique.store') }}" method="POST" enctype="multipart/form-data" id="addMusiqueForm">
                @csrf

                <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">
                            <i class="mr-2 ri-text"></i>Titre de la musique
                        </label>
                        <input type="text" name="titre" id="titre_input" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ex: Joyeux anniversaire">
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">
                            <i class="mr-2 ri-folder-music-line"></i>Catégorie Supriz
                        </label>
                        <select name="sous_categorie_id" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach($sousCategories as $sousCategorie)
                                <option value="{{ $sousCategorie->id }}">{{ $sousCategorie->name_sous_categories }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Zone de drop pour l'audio -->
                <div class="mb-6">
                    <label class="block mb-3 font-semibold text-gray-700">
                        <i class="mr-2 ri-file-music-line"></i>Fichier audio
                    </label>
                    <div class="audio-drop-zone" id="audioDropZone">
                        <input type="file" name="fichier_audio" id="audio_file" accept="audio/*" required class="hidden">
                        <input type="hidden" name="duree" id="audio_duration">

                        <div id="drop-area-content">
                            <i class="mb-3 text-5xl ri-upload-cloud-2-line" style="color: #005792;"></i>
                            <p class="mb-2 text-lg font-semibold" style="color: #005792;">
                                Glissez votre fichier audio ici
                            </p>
                            <p class="mb-3 text-sm text-gray-500">
                                ou cliquez pour parcourir
                            </p>
                            <button type="button" onclick="document.getElementById('audio_file').click()"
                                    class="px-6 py-2 text-white rounded-lg" style="background-color: #005792;">
                                <i class="mr-2 ri-folder-open-line"></i>Parcourir
                            </button>
                        </div>

                        <div id="audio-preview" class="hidden">
                            <i class="mb-3 text-5xl ri-music-2-fill" style="color: #10B981;"></i>
                            <p class="mb-2 font-semibold text-gray-700" id="file-name"></p>
                            <p id="duration_display" class="mb-2 text-sm font-semibold text-green-600"></p>
                            <p id="duration_error" class="mb-2 text-sm font-semibold text-red-500 hidden"></p>
                            <audio id="audio_player" controls class="mx-auto mt-3" style="width: 100%; max-width: 400px;">
                                <source id="audio_source" type="audio/mpeg">
                            </audio>
                            <button type="button" onclick="removeAudio()"
                                    class="px-4 py-2 mt-3 text-white bg-red-500 rounded-lg hover:bg-red-600">
                                <i class="mr-2 ri-delete-bin-line"></i>Supprimer
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" id="submitBtn"
                            class="text-white rounded-lg font-semibold transition-all hover:opacity-90 flex items-center"
                            style="background-color: #005792; padding: 14px 32px;">
                        <i class="mr-2 text-lg ri-music-2-fill"></i>
                        <span>Ajouter la musique</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des musiques par catégorie -->
        <div class="p-8 bg-white rounded-20" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <i class="mr-3 text-3xl ri-playlist-line" style="color: #005792;"></i>
                    <h2 class="text-2xl font-bold" style="color: #005792;">
                        Musiques par catégorie
                    </h2>
                </div>
            </div>

            @forelse($musiquesGroupees as $categorie => $musiques)
                <!-- En-tête de catégorie -->
                <div class="p-4 mb-4 rounded-lg" style="background: linear-gradient(135deg, #005792 0%, #0077b6 100%);">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="mr-3 text-2xl text-white ri-folder-music-fill"></i>
                            <h3 class="text-xl font-bold text-white">{{ $categorie }}</h3>
                        </div>
                        <span class="px-3 py-1 text-sm font-semibold text-black bg-white bg-opacity-20 rounded-full">
                            {{ $musiques->count() }} musique(s)
                        </span>
                    </div>
                </div>

                <!-- Musiques de cette catégorie -->
                <div class="mb-8">
                    @foreach($musiques as $musique)
                        <div class="music-card">
                            <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                                <!-- Audio Player -->
                                <div class="flex items-center flex-1 min-w-0">
                                    <div class="flex items-center justify-center w-12 h-12 mr-4 rounded-full" style="background-color: #E5F2FF;">
                                        <i class="text-xl ri-music-2-fill" style="color: #005792;"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        @if($musique->fichier_audio)
                                            <audio controls style="width: 100%; max-width: 300px; height: 40px;">
                                                <source src="{{ asset('storage/' . $musique->fichier_audio) }}" type="audio/mpeg">
                                                Votre navigateur ne supporte pas l'audio.
                                            </audio>
                                        @else
                                            <span class="text-gray-400">Aucun audio</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Info -->
                                <div class="flex items-center flex-1">
                                    <p class="font-bold text-gray-800">{{ $musique->titre }}</p>
                                </div>

                                <!-- Durée -->
                                <div class="flex items-center">
                                    <i class="mr-2 text-gray-400 ri-time-line"></i>
                                    @if($musique->duree)
                                        <span class="font-semibold text-gray-600">{{ $musique->duree }}s</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>

                                <!-- Statut -->
                                <div>
                                    <form action="{{ route('musique.toggleStatus', $musique->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 font-semibold text-white rounded-lg transition-all hover:opacity-90"
                                                style="background-color: {{ $musique->is_active ? '#10B981' : '#EF4444' }};">
                                            @if($musique->is_active)
                                                <i class="mr-1 ri-check-line"></i>Actif
                                            @else
                                                <i class="mr-1 ri-close-line"></i>Inactif
                                            @endif
                                        </button>
                                    </form>
                                </div>

                                <!-- Actions -->
                                <div>
                                    <form action="{{ route('musique.destroy', $musique->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-4 py-2 font-semibold text-white transition-all bg-red-500 rounded-lg hover:bg-red-600"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette musique ?')">
                                            <i class="mr-1 ri-delete-bin-line"></i>Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="py-12 text-center">
                    <i class="mb-4 text-6xl text-gray-300 ri-music-2-line"></i>
                    <p class="text-lg text-gray-500">Aucune musique enregistrée</p>
                    <p class="text-sm text-gray-400">Commencez par ajouter votre première musique ci-dessus</p>
                </div>
            @endforelse
        </div>

    </main>
    <!-- end: Main -->

    <script>
        const audioFile = document.getElementById('audio_file');
        const dropZone = document.getElementById('audioDropZone');
        const dropContent = document.getElementById('drop-area-content');
        const audioPreview = document.getElementById('audio-preview');
        const audioPlayer = document.getElementById('audio_player');
        const audioSource = document.getElementById('audio_source');
        const fileName = document.getElementById('file-name');
        const durationDisplay = document.getElementById('duration_display');
        const durationError = document.getElementById('duration_error');
        const submitBtn = document.getElementById('submitBtn');

        // Click to upload
        dropZone.addEventListener('click', function(e) {
            if (e.target !== dropZone && e.target.closest('#audio-preview')) return;
            audioFile.click();
        });

        // Drag and drop
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropZone.classList.remove('dragover');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                audioFile.files = files;
                handleAudioFile(files[0]);
            }
        });

        // File input change
        audioFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                handleAudioFile(file);
            }
        });

        function handleAudioFile(file) {
            fileName.textContent = file.name;

            // Auto-remplir le titre avec le nom du fichier (sans l'extension)
            const titreInput = document.getElementById('titre_input');
            if (!titreInput.value || titreInput.value === '') {
                const fileNameWithoutExt = file.name.replace(/\.[^/.]+$/, '');
                titreInput.value = fileNameWithoutExt;
            }

            const objectUrl = URL.createObjectURL(file);
            audioSource.src = objectUrl;
            audioPlayer.load();

            const audio = new Audio();
            audio.addEventListener('loadedmetadata', function() {
                const duration = Math.round(audio.duration);
                document.getElementById('audio_duration').value = duration;

                durationDisplay.textContent = `✓ Durée: ${duration} secondes`;

                if (duration > 20) {
                    durationError.textContent = `⚠️ Erreur: La durée ne doit pas dépasser 20 secondes!`;
                    durationError.classList.remove('hidden');
                    durationDisplay.classList.add('hidden');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    durationError.classList.add('hidden');
                    durationDisplay.classList.remove('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                URL.revokeObjectURL(objectUrl);
            });

            audio.src = objectUrl;

            dropContent.classList.add('hidden');
            audioPreview.classList.remove('hidden');
        }

        function removeAudio() {
            audioFile.value = '';
            audioSource.src = '';
            audioPlayer.load();
            dropContent.classList.remove('hidden');
            audioPreview.classList.add('hidden');
            durationDisplay.textContent = '';
            durationError.classList.add('hidden');
            document.getElementById('titre_input').value = '';
            document.getElementById('audio_duration').value = '';
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    </script>

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>
