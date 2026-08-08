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
    <title>Supriz - Arrière-plans</title>
    <style>
        .image-drop-zone {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background-color: #f9fafb;
        }
        .image-drop-zone:hover {
            border-color: #005792;
            background-color: #E5F2FF;
        }
        .image-drop-zone.dragover {
            border-color: #005792;
            background-color: #E5F2FF;
        }
        .bg-card {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 16px;
            background: white;
            transition: all 0.3s;
        }
        .bg-card:hover {
            box-shadow: 0 4px 12px rgba(0, 87, 146, 0.1);
            border-color: #005792;
        }
        .bg-preview {
            width: 120px;
            height: 180px;
            object-fit: cover;
            border-radius: 12px;
        }
        /* Scrollbar personnalisée */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #005792;
            border-radius: 10px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #003d66;
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

        <div class="pt-4 pb-0 px-4 md:pt-6 md:px-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <img class="max-w-full h-auto" src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="">
                <h4 class="font-bold text-right text-white text-base md:text-lg">Gestion des Arrière-plans</h4>
                <div class="flex flex-wrap items-center gap-2">
                    <img class="mr-3 max-w-full h-auto" src="{{ asset('/images/svg/menu-alt_svgrepo.com.svg') }}" alt="">
                    <div class="flex relative">
                        <input type="text" class="pr-10 rounded-20 w-full max-w-full" placeholder="Recherche">
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

        <!-- Formulaire d'ajout d'arrière-plan -->
        <div class="p-4 md:p-8 mb-6 bg-white rounded-20 mx-3 md:mx-0" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <div class="flex items-center mb-6">
                <i class="mr-3 text-2xl md:text-3xl ri-image-fill" style="color: #005792;"></i>
                <h2 class="text-lg md:text-2xl font-bold" style="color: #005792;">Ajouter un arrière-plan</h2>
            </div>

            <div class="p-4 mb-6 rounded-lg" style="background-color: #E5F2FF; border-left: 4px solid #005792;">
                <div class="flex items-start">
                    <i class="mt-1 mr-3 text-xl ri-information-line" style="color: #005792;"></i>
                    <div>
                        <p class="font-semibold" style="color: #005792;">Instructions :</p>
                        <ul class="mt-2 ml-4 text-sm list-disc" style="color: #005792;">
                            <li>Formats acceptés : JPG, JPEG, PNG, WebP</li>
                            <li>Taille recommandée : 1080 x 1920 pixels (format portrait)</li>
                            <li>Taille maximale : 2 Mo</li>
                            <li>Chaque arrière-plan doit être associé à une catégorie Supriz</li>
                        </ul>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.arriere-plan.store') }}" method="POST" enctype="multipart/form-data" id="addBgForm">
                @csrf

                <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">
                            <i class="mr-2 ri-text"></i>Nom de l'arrière-plan
                        </label>
                        <input type="text" name="nom" id="nom_input" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ex: Ballons anniversaire">
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">
                            <i class="mr-2 ri-folder-image-line"></i>Catégorie Supriz
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

                <!-- Zone de drop pour l'image -->
                <div class="mb-6">
                    <label class="block mb-3 font-semibold text-gray-700">
                        <i class="mr-2 ri-image-add-line"></i>Image d'arrière-plan
                    </label>
                    <div class="image-drop-zone" id="imageDropZone">
                        <input type="file" name="image" id="image_file" accept="image/*" required class="hidden">

                        <div id="drop-area-content">
                            <i class="mb-3 text-5xl ri-upload-cloud-2-line" style="color: #005792;"></i>
                            <p class="mb-2 text-lg font-semibold" style="color: #005792;">
                                Glissez votre image ici
                            </p>
                            <p class="mb-3 text-sm text-gray-500">
                                ou cliquez pour parcourir
                            </p>
                            <button type="button" onclick="document.getElementById('image_file').click()"
                                    class="px-6 py-2 text-white rounded-lg" style="background-color: #005792;">
                                <i class="mr-2 ri-folder-open-line"></i>Parcourir
                            </button>
                        </div>

                        <div id="image-preview" class="hidden">
                            <img id="preview_img" src="" alt="Aperçu" class="mx-auto mb-3" style="max-height: 200px; border-radius: 12px;">
                            <p class="mb-2 font-semibold text-gray-700" id="file-name"></p>
                            <button type="button" onclick="removeImage()"
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
                        <i class="mr-2 text-lg ri-image-add-fill"></i>
                        <span>Ajouter l'arrière-plan</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des arrière-plans par catégorie -->
        <div class="p-4 md:p-8 bg-white rounded-20 mx-3 md:mx-0" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <i class="mr-3 text-2xl md:text-3xl ri-gallery-line" style="color: #005792;"></i>
                    <h2 class="text-lg md:text-2xl font-bold" style="color: #005792;">
                        Arrière-plans par catégorie
                    </h2>
                </div>
            </div>

            @forelse($arrierePlansGroupes as $categorie => $arrierePlans)
                <!-- En-tête de catégorie -->
                <div class="p-4 mb-4 rounded-lg" style="background: linear-gradient(135deg, #005792 0%, #0077b6 100%);">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center min-w-0">
                            <i class="mr-3 text-2xl text-white ri-folder-image-fill flex-shrink-0"></i>
                            <h3 class="text-base md:text-xl font-bold text-white truncate">{{ $categorie }}</h3>
                        </div>
                        <span class="px-3 py-1 text-xs md:text-sm font-semibold text-black bg-white bg-opacity-20 rounded-full whitespace-nowrap">
                            {{ $arrierePlans->count() }} arrière-plan(s)
                        </span>
                    </div>
                </div>

                <!-- Arrière-plans de cette catégorie - Défilement horizontal -->
                <div class="mb-8 overflow-x-auto pb-4" style="scrollbar-width: thin;">
                    <div class="flex gap-4" style="min-width: max-content;">
                        @foreach($arrierePlans as $arrierePlan)
                            <div class="bg-card flex-shrink-0 js-recherche-item" style="width: 160px;">
                                <!-- Image preview -->
                                <div class="mb-3">
                                    @if($arrierePlan->image)
                                        <img src="{{ asset('storage/' . $arrierePlan->image) }}"
                                             alt="{{ $arrierePlan->nom }}"
                                             class="bg-preview w-full">
                                    @else
                                        <div class="bg-preview w-full bg-gray-200 flex items-center justify-center">
                                            <i class="text-4xl text-gray-400 ri-image-line"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Info -->
                                <p class="mb-2 font-bold text-gray-800 text-sm truncate" title="{{ $arrierePlan->nom }}">
                                    {{ $arrierePlan->nom }}
                                </p>

                                <!-- Actions -->
                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                    <!-- Toggle Status -->
                                    <form action="{{ route('admin.arriere-plan.toggleStatus', $arrierePlan->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <button type="submit" style="width: 100%; padding: 8px 12px; font-size: 12px; font-weight: 600; color: white; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px; background-color: {{ $arrierePlan->is_active ? '#10B981' : '#EF4444' }};">
                                            @if($arrierePlan->is_active)
                                                <i class="ri-check-line"></i> <span>Actif</span>
                                            @else
                                                <i class="ri-close-line"></i> <span>Inactif</span>
                                            @endif
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form action="{{ route('admin.arriere-plan.destroy', $arrierePlan->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="width: 100%; padding: 8px 12px; font-size: 12px; font-weight: 600; color: white; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px; background-color: #EF4444;"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet arrière-plan ?')">
                                            <i class="ri-delete-bin-line"></i> <span>Supprimer</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <i class="mb-4 text-6xl text-gray-300 ri-image-line"></i>
                    <p class="text-lg text-gray-500">Aucun arrière-plan enregistré</p>
                    <p class="text-sm text-gray-400">Commencez par ajouter votre premier arrière-plan ci-dessus</p>
                </div>
            @endforelse
        </div>

    </main>
    <!-- end: Main -->

    <script>
        const imageFile = document.getElementById('image_file');
        const dropZone = document.getElementById('imageDropZone');
        const dropContent = document.getElementById('drop-area-content');
        const imagePreview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview_img');
        const fileName = document.getElementById('file-name');

        // Click to upload
        dropZone.addEventListener('click', function(e) {
            if (e.target !== dropZone && e.target.closest('#image-preview')) return;
            imageFile.click();
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
                imageFile.files = files;
                handleImageFile(files[0]);
            }
        });

        // File input change
        imageFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                handleImageFile(file);
            }
        });

        function handleImageFile(file) {
            fileName.textContent = file.name;

            // Auto-remplir le nom avec le nom du fichier (sans l'extension)
            const nomInput = document.getElementById('nom_input');
            if (!nomInput.value || nomInput.value === '') {
                const fileNameWithoutExt = file.name.replace(/\.[^/.]+$/, '');
                nomInput.value = fileNameWithoutExt;
            }

            const objectUrl = URL.createObjectURL(file);
            previewImg.src = objectUrl;

            dropContent.classList.add('hidden');
            imagePreview.classList.remove('hidden');
        }

        function removeImage() {
            imageFile.value = '';
            previewImg.src = '';
            dropContent.classList.remove('hidden');
            imagePreview.classList.add('hidden');
            document.getElementById('nom_input').value = '';
        }
    </script>

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @include('partials.recherche-liste')
</body>

</html>
