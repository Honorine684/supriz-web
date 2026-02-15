<!doctype html>
<html lang="en">

<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href={{ asset('css/main.css') }}>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Supriz - Catégorie</title>
</head>


<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">
    @include('partials.gtm-body')

    <!-- start: Sidebar -->
    @include('components.sidebar')
    <!-- end: Sidebar -->

    <!-- start: Main -->
    <main
        class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20 ">

        <div class="pt-6 pb-0 pl-6 pr-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex items-center justify-between mb-5">
                <img src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="">
                <h4 class="font-bold text-right text-white">Catégoeries</h4>
                <div class="flex">
                    <img class="mr-3" src="{{ asset('/images/svg/menu-alt_svgrepo.com.svg') }}" alt="">

                    <div class="flex">
                        <input type="text" class="pr-10 rounded-20" placeholder="Recherche">
                        <img class="absolute cursor-pointer search_icon"
                            src="{{ asset('/images/svg/search-alt-1_svgrepo.com.svg') }}" alt="">
                    </div>
                </div>
            </div>

        </div>
        @if (session('success'))
            <div class="bg-green-500 text-center p-2 rounded text-white">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500 text-center p-2 rounded text-white">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-500 text-center p-2 rounded text-white">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif


        <form class="mt-5" action="{{ isset($sousCategorie) ? route('admin.categorie.update', $sousCategorie->id) : route('admin.add.categorie') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($sousCategorie))
                @method('PUT')
            @endif

            <h2 class="font-bold mb-4">{{ isset($sousCategorie) ? 'Modifier' : 'Ajouter' }} une Sous-Catégorie</h2>

            <div class="mb-4">
                <label for="categorie" class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                <select name="categorie"  id="categorie" class="block w-full p-2 border rounded">
                    <option value="" disabled {{ !isset($sousCategorie) ? 'selected' : '' }}>Choisir une catégorie</option>
                    <option value="h" {{ (isset($sousCategorie) && $sousCategorie->categorie->name_id_categories == 'h') ? 'selected' : '' }}>Homme</option>
                    <option value="f" {{ (isset($sousCategorie) && $sousCategorie->categorie->name_id_categories == 'f') ? 'selected' : '' }}>Femme</option>
                    <option value="e" {{ (isset($sousCategorie) && $sousCategorie->categorie->name_id_categories == 'e') ? 'selected' : '' }}>Enfant</option>
                    <option value="s" {{ (isset($sousCategorie) && $sousCategorie->categorie->name_id_categories == 's') ? 'selected' : '' }}>Supriz</option>
                </select>
                @error('categorie')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="sousCategorie" class="block text-sm font-medium text-gray-700 mb-2">Sous-Catégorie</label>
                <input type="text" name="sousCategorie" id="sousCategorie" class="block w-full p-2 border rounded"
                       placeholder="Nom de la sous-catégorie"
                       value="{{ isset($sousCategorie) ? $sousCategorie->name_sous_categories : '' }}">
                @error('sousCategorie')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4" id="placeholder_field" style="display: none;">
                <label for="placeholder_texte" class="block text-sm font-medium text-gray-700 mb-2">
                    Placeholder du titre (Optionnel)
                    <span class="text-xs text-gray-500">- Texte affiché dans le champ titre du formulaire</span>
                </label>
                <input type="text" name="placeholder_texte" id="placeholder_texte" class="block w-full p-2 border rounded"
                       placeholder="Ex: Joyeux anniversaire Admin"
                       value="{{ isset($sousCategorie) ? $sousCategorie->placeholder_texte : '' }}">
                <p class="text-xs text-gray-500 mt-1">Ce texte sera affiché comme exemple dans le champ "Titre du message"</p>
                @error('placeholder_texte')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="prix" class="block text-sm font-medium text-gray-700 mb-2">Prix (Optionnel)</label>
                <input type="number" name="prix" id="prix" class="block w-full p-2 border rounded"
                       placeholder="Prix de la sous-catégorie"
                       value="{{ isset($sousCategorie) ? $sousCategorie->prix : '' }}">
                @error('prix')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Champ icône -->
            <div class="mb-4">
                <label for="icone" class="block text-sm font-medium text-gray-700 mb-2">Icône (Optionnel - 40x40px recommandé)</label>

                <!-- Zone de drag and drop pour icône -->
                <div id="iconeDropZone" style="border: 2px dashed #d1d5db; border-radius: 8px; padding: 20px; text-align: center; cursor: pointer; transition: all 0.3s; background-color: #fafbfc;">
                    <input type="file" name="icone" id="icone" accept="image/*" class="hidden">

                    <div id="iconeDropContent">
                        <div style="width: 60px; height: 60px; margin: 0 auto 12px; border-radius: 50%; background-color: #E5F2FF; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 30px; height: 30px; color: #005792;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p style="font-size: 14px; color: #374151; font-weight: 500; margin-bottom: 4px;">Cliquez ou glissez votre icône</p>
                        <p style="font-size: 12px; color: #6b7280;">PNG, JPG, SVG - Max 2MB</p>
                    </div>

                    <!-- Prévisualisation de l'icône -->
                    <div id="iconePreview" class="hidden">
                        <img id="iconePreviewImg" src="" alt="Aperçu icône" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 3px solid #005792; margin: 0 auto 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <p id="iconeFileName" style="font-size: 13px; color: #374151; font-weight: 500; margin-bottom: 12px;"></p>
                        <button type="button" id="removeIcone" style="padding: 6px 16px; font-size: 13px; color: white; background-color: #dc2626; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                            Supprimer
                        </button>
                    </div>
                </div>

                @if(isset($sousCategorie) && $sousCategorie->icone)
                <div style="margin-top: 12px; padding: 12px; background-color: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb; display: flex; align-items: center; gap: 12px;">
                    <img src="{{ asset('storage/' . $sousCategorie->icone) }}" alt="Icône actuelle" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%; border: 2px solid #005792;">
                    <div>
                        <p style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">Icône actuelle</p>
                        <p style="font-size: 13px; color: #374151; font-weight: 500;">Uploadez une nouvelle icône pour remplacer</p>
                    </div>
                </div>
                @endif

                @error('icone')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Champ image pour Supriz seulement -->
            <div class="mb-4" id="imageField" style="display: none;">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image/GIF (pour Supriz)</label>

                <!-- Zone de drag and drop -->
                <div id="dropZone" style="border: 2px dashed #d1d5db; border-radius: 8px; padding: 24px; text-align: center; cursor: pointer; transition: all 0.3s;">
                    <div id="dropZoneContent">
                        <svg class="mx-auto text-gray-400" style="width: 48px; height: 48px;" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div style="margin-top: 16px; display: flex; justify-content: center; align-items: center; font-size: 14px; color: #6b7280;">
                            <label for="image" style="cursor: pointer; font-weight: 600; color: #2563eb;">
                                <span>Cliquez pour sélectionner</span>
                            </label>
                            <p style="padding-left: 4px;">ou glissez-déposez</p>
                        </div>
                        <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">PNG, JPG, GIF jusqu'à 5MB</p>
                    </div>

                    <!-- Prévisualisation de l'image -->
                    <div id="imagePreview" class="hidden">
                        <img id="previewImg" src="" alt="Prévisualisation" style="margin: 0 auto; max-height: 192px; border-radius: 8px;">
                        <p id="fileName" style="margin-top: 8px; font-size: 14px; color: #374151; font-weight: 500;"></p>
                        <button type="button" id="removeImage" style="margin-top: 8px; font-size: 14px; color: #dc2626; font-weight: 500; background: none; border: none; cursor: pointer;">
                            Supprimer
                        </button>
                    </div>
                </div>

                <input type="file" name="image" id="image" accept="image/*,.gif" class="hidden">

                @if(isset($sousCategorie) && $sousCategorie->image)
                <div style="margin-top: 0.75rem; padding: 0.5rem; background-color: #f9fafb; border-radius: 6px;">
                    <p style="font-size: 0.7rem; color: #6b7280; margin-bottom: 0.5rem;">Image actuelle :</p>
                    <img src="{{ asset('storage/' . $sousCategorie->image) }}" alt="Image actuelle" style="width: 64px; height: 64px; object-fit: cover; border-radius: 6px; border: 1px solid #d1d5db;">
                </div>
                @endif

                @error('image')
                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <input type="submit" name="submit"
                       class="w-full relative right-0 rounded p-2 text-white cursor-pointer btn-design mt-1"
                       value="{{ isset($sousCategorie) ? 'Modifier' : 'Enregistrer' }}">
            </div>
        </form>


        <!-- Section Catégories Supriz Prédéfinies -->
        <div style="margin-top: 3rem; margin-bottom: 2rem;">
            <h2 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem; color: #1f2937;">Catégories Supriz Prédéfinies</h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                @foreach($suprizPredefinies as $supriz)
                <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 2px solid #E5F2FF;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 style="font-size: 1.125rem; font-weight: bold; color: #005792;">{{ $supriz->name_sous_categories }}</h3>
                        <form action="{{ route('admin.categorie.toggleStatus', $supriz->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit"
                                    style="padding: 0.25rem 0.75rem; border-radius: 9999px; border: none; cursor: pointer; font-size: 0.7rem; font-weight: 700; background-color: {{ $supriz->is_active ? '#dcfce7' : '#fee2e2' }}; color: {{ $supriz->is_active ? '#166534' : '#991b1b' }};">
                                {{ $supriz->is_active ? '✓ Actif' : '✗ Inactif' }}
                            </button>
                        </form>
                    </div>

                    <!-- Icône -->
                    <div style="margin-bottom: 1rem; padding: 1rem; background-color: #f9fafb; border-radius: 10px; border: 1px solid #e5e7eb;">
                        <p style="font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Icône</p>
                        <div style="display: flex; align-items: center; gap: 14px;">
                            @if($supriz->icone)
                                <img src="{{ asset('storage/' . $supriz->icone) }}"
                                     alt="Icône {{ $supriz->name_sous_categories }}"
                                     style="width: 56px; height: 56px; object-fit: cover; border-radius: 50%; border: 3px solid #005792; box-shadow: 0 2px 8px rgba(0, 87, 146, 0.2);">
                            @else
                                <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #E5F2FF 0%, #B8DAFF 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px dashed #005792;">
                                    <svg style="width: 24px; height: 24px; color: #005792;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <form action="{{ route('admin.categorie.updateSuprizIcone', $supriz->id) }}" method="POST" enctype="multipart/form-data" style="flex: 1;">
                                @csrf
                                <label style="display: block; width: 100%; padding: 10px 16px; font-size: 13px; font-weight: 500; color: white; background-color: #005792; border-radius: 8px; cursor: pointer; text-align: center; transition: all 0.2s;">
                                    <input type="file" name="icone" accept="image/*" onchange="this.form.submit()" style="display: none;">
                                    Choisir une icône
                                </label>
                            </form>
                        </div>
                    </div>

                    <!-- Image principale -->
                    <div style="margin-bottom: 1rem; padding: 1rem; background-color: #f9fafb; border-radius: 10px; border: 1px solid #e5e7eb;">
                        <p style="font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Image principale</p>

                        @if($supriz->image)
                        <div style="position: relative; margin-bottom: 12px;">
                            <img src="{{ asset('storage/' . $supriz->image) }}"
                                 alt="{{ $supriz->name_sous_categories }}"
                                 style="width: 100%; height: 180px; object-fit: cover; border-radius: 10px; border: 2px solid #005792; box-shadow: 0 2px 8px rgba(0, 87, 146, 0.15);">
                        </div>
                        @else
                        <div style="margin-bottom: 12px; text-align: center; padding: 3rem 1rem; background: linear-gradient(135deg, #fafbfc 0%, #E5F2FF 100%); border-radius: 10px; border: 2px dashed #005792;">
                            <div style="width: 60px; height: 60px; margin: 0 auto 12px; border-radius: 50%; background-color: #005792; display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 30px; height: 30px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p style="font-size: 14px; font-weight: 500; color: #005792;">Aucune image</p>
                        </div>
                        @endif

                        <form action="{{ route('admin.categorie.updateSuprizImage', $supriz->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label style="display: block; width: 100%; padding: 10px 16px; font-size: 13px; font-weight: 500; color: white; background-color: #005792; border-radius: 8px; cursor: pointer; text-align: center; transition: all 0.2s;">
                                <input type="file" name="image" accept="image/*,.gif" onchange="this.form.submit()" style="display: none;">
                                {{ $supriz->image ? 'Changer l\'image' : 'Ajouter une image' }}
                            </label>
                        </form>
                    </div>

                    <!-- Image cadeau (Gift) -->
                    <div style="margin-bottom: 1rem; padding: 1rem; background-color: #fff7ed; border-radius: 10px; border: 1px solid #fed7aa;">
                        <p style="font-size: 12px; font-weight: 600; color: #c2410c; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">🎁 Image cadeau (Gift)</p>

                        @if($supriz->gift_image)
                        <div style="position: relative; margin-bottom: 12px; text-align: center;">
                            <img src="{{ asset('storage/' . $supriz->gift_image) }}"
                                 alt="Gift {{ $supriz->name_sous_categories }}"
                                 style="width: 120px; height: 120px; object-fit: contain; border-radius: 10px; border: 2px solid #ea580c; box-shadow: 0 2px 8px rgba(234, 88, 12, 0.15);">
                        </div>
                        @else
                        <div style="margin-bottom: 12px; text-align: center; padding: 2rem 1rem; background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border-radius: 10px; border: 2px dashed #ea580c;">
                            <div style="width: 50px; height: 50px; margin: 0 auto 10px; border-radius: 50%; background-color: #ea580c; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 24px;">🎁</span>
                            </div>
                            <p style="font-size: 13px; font-weight: 500; color: #ea580c;">Aucune image cadeau</p>
                        </div>
                        @endif

                        <form action="{{ route('admin.categorie.updateSuprizGiftImage', $supriz->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label style="display: block; width: 100%; padding: 10px 16px; font-size: 13px; font-weight: 500; color: white; background-color: #ea580c; border-radius: 8px; cursor: pointer; text-align: center; transition: all 0.2s;">
                                <input type="file" name="gift_image" accept="image/*,.gif" onchange="this.form.submit()" style="display: none;">
                                {{ $supriz->gift_image ? 'Changer l\'image cadeau' : 'Ajouter une image cadeau' }}
                            </label>
                        </form>
                        <p style="font-size: 11px; color: #9a3412; margin-top: 8px; text-align: center;">Cette image sera affichée comme cadeau dans l'application</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>


        <div style="margin-top: 2rem;">
            <h2 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem; color: #1f2937;">Liste des sous-catégories</h2>

            <div style="background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #005792;">
                            <th style="padding: 1rem 1.5rem; text-align: left; color: white; font-weight: bold; font-size: 0.875rem; text-transform: uppercase;">Nom</th>
                            <th style="padding: 1rem 1.5rem; text-align: left; color: white; font-weight: bold; font-size: 0.875rem; text-transform: uppercase;">Catégorie</th>
                            <th style="padding: 1rem 1.5rem; text-align: left; color: white; font-weight: bold; font-size: 0.875rem; text-transform: uppercase;">Prix</th>
                            <th style="padding: 1rem 1.5rem; text-align: left; color: white; font-weight: bold; font-size: 0.875rem; text-transform: uppercase;">Icône</th>
                            <th style="padding: 1rem 1.5rem; text-align: left; color: white; font-weight: bold; font-size: 0.875rem; text-transform: uppercase;">Image</th>
                            <th style="padding: 1rem 1.5rem; text-align: center; color: white; font-weight: bold; font-size: 0.875rem; text-transform: uppercase;">Statut</th>
                            <th style="padding: 1rem 1.5rem; text-align: center; color: white; font-weight: bold; font-size: 0.875rem; text-transform: uppercase;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($sousCategories && count($sousCategories) > 0)
                            @foreach ($sousCategories as $sousCategorie)
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 1rem 1.5rem;">
                                        <span style="font-size: 0.875rem; font-weight: 600; color: #1f2937;">
                                            {{ $sousCategorie->name_sous_categories }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        @php
                                            $bgColor = '#E5F2FF';
                                            $textColor = '#005792';
                                            if($sousCategorie->categorie->name_id_categories == 'f') {
                                                $bgColor = '#fce7f3';
                                                $textColor = '#9f1239';
                                            } elseif($sousCategorie->categorie->name_id_categories == 'e') {
                                                $bgColor = '#dcfce7';
                                                $textColor = '#166534';
                                            } elseif($sousCategorie->categorie->name_id_categories == 's') {
                                                $bgColor = '#E5F2FF';
                                                $textColor = '#005792';
                                            }
                                        @endphp
                                        <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background-color: {{ $bgColor }}; color: {{ $textColor }};">
                                            {{ $sousCategorie->categorie->libelle_homme_categories }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        <span style="font-size: 0.875rem; font-weight: 500; color: #374151;">
                                            {{ $sousCategorie->prix ? number_format($sousCategorie->prix, 0, ',', ' ') . ' FCFA' : 'N/A' }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        <div style="display: flex; align-items: center; justify-content: center;">
                                            @if($sousCategorie->icone)
                                                <img src="{{ asset('storage/' . $sousCategorie->icone) }}"
                                                     alt="Icône"
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%; border: 3px solid #005792; box-shadow: 0 2px 6px rgba(0, 87, 146, 0.2);">
                                            @else
                                                <div style="width: 50px; height: 50px; background-color: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px dashed #d1d5db;">
                                                    <svg style="width: 20px; height: 20px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        @if($sousCategorie->image)
                                            <div style="display: flex; align-items: center; justify-content: flex-start;">
                                                <img src="{{ asset('storage/' . $sousCategorie->image) }}"
                                                     alt="{{ $sousCategorie->name_sous_categories }}"
                                                     style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 2px solid #d1d5db; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                            </div>
                                        @else
                                            <span style="font-size: 0.75rem; color: #9ca3af; font-style: italic;">Aucune image</span>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem 1.5rem; text-align: center;">
                                        <form action="{{ route('admin.categorie.toggleStatus', $sousCategorie->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit"
                                                    style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 9999px; border: none; cursor: pointer; font-size: 0.75rem; font-weight: 700; transition: all 0.2s; background-color: {{ $sousCategorie->is_active ? '#dcfce7' : '#fee2e2' }}; color: {{ $sousCategorie->is_active ? '#166534' : '#991b1b' }};"
                                                    onmouseover="this.style.opacity='0.8'"
                                                    onmouseout="this.style.opacity='1'">
                                                <span style="width: 8px; height: 8px; border-radius: 50%; background-color: {{ $sousCategorie->is_active ? '#22c55e' : '#ef4444' }};"></span>
                                                {{ $sousCategorie->is_active ? 'Activé' : 'Désactivé' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        @php
                                            $categoriesSuprizPredefinies = ['Anniversaire', 'Mariage', 'Vœux', 'Nouvel an', 'Noël'];
                                            $isPredefined = in_array($sousCategorie->name_sous_categories, $categoriesSuprizPredefinies) && $sousCategorie->categorie->name_id_categories == 's';
                                        @endphp

                                        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                            @if(!$isPredefined)
                                                <a href="{{ route('admin.categorie.edit', $sousCategorie->id) }}"
                                                   style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background-color: #A4A9B7BF; transition: background-color 0.2s;"
                                                   onmouseover="this.style.backgroundColor='#8e94a3'"
                                                   onmouseout="this.style.backgroundColor='#A4A9B7BF'"
                                                   title="Modifier">
                                                    <img src="{{ asset('/images/svg/edit-3_svgrepo.com.svg') }}" alt="Modifier" style="width: 20px; height: 20px;">
                                                </a>
                                                <form action="{{ route('admin.categorie.destroy', $sousCategorie->id) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette sous-catégorie ?')"
                                                      style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background-color: #fee2e2; border: none; cursor: pointer; transition: background-color 0.2s;"
                                                            onmouseover="this.style.backgroundColor='#fecaca'"
                                                            onmouseout="this.style.backgroundColor='#fee2e2'"
                                                            title="Supprimer">
                                                        <img src="{{ asset('/images/svg/delete-left_svgrepo.com.svg') }}" alt="Supprimer" style="width: 20px; height: 20px;">
                                                    </button>
                                                </form>
                                            @else
                                                <span style="font-size: 0.75rem; color: #6b7280; font-style: italic;">Catégorie système</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" style="padding: 3rem 1.5rem; text-align: center;">
                                    <p style="color: #6b7280; font-weight: 500; margin-bottom: 0.5rem;">Aucune sous-catégorie disponible</p>
                                    <p style="color: #9ca3af; font-size: 0.875rem;">Ajoutez votre première sous-catégorie ci-dessus</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>



        </div>
    </main>
    <!-- end: Main -->

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="js/chart.js"></script>
    <script src="{{ asset('js/imagefile.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var categorie = document.querySelector("#categorie");
            var prix = document.querySelector('#prix');
            var imageField = document.querySelector('#imageField');

            if (!categorie || !prix || !imageField) {
                console.error('Éléments introuvables');
                return;
            }

            function toggleFields() {
                if (categorie.selectedIndex == 4) { // Supriz
                    prix.disabled = false;
                    imageField.style.display = 'block';
                } else {
                    prix.disabled = true;
                    imageField.style.display = 'none';
                }
            }

            // Appliquer au chargement de la page
            toggleFields();

            // Appliquer lors du changement
            categorie.addEventListener("change", toggleFields);

            // Gestion du drag and drop et prévisualisation d'image
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('image');
            const dropZoneContent = document.getElementById('dropZoneContent');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            const fileName = document.getElementById('fileName');
            const removeImageBtn = document.getElementById('removeImage');

            if (!dropZone || !fileInput) {
                console.error('Zone de drop introuvable');
                return;
            }

            // Empêcher les comportements par défaut
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Mettre en évidence la zone de drop
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropZone.style.borderColor = '#3b82f6';
                dropZone.style.backgroundColor = '#eff6ff';
            }

            function unhighlight(e) {
                dropZone.style.borderColor = '#d1d5db';
                dropZone.style.backgroundColor = 'transparent';
            }

            // Gérer le drop
            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    fileInput.files = files;
                    handleFiles(files);
                }
            }

            // Gérer la sélection par clic
            fileInput.addEventListener('change', function(e) {
                if (this.files.length > 0) {
                    handleFiles(this.files);
                }
            });

            // Clic sur la zone
            dropZone.addEventListener('click', function(e) {
                e.preventDefault();
                fileInput.click();
            });

            // Traiter les fichiers
            function handleFiles(files) {
                const file = files[0];

                // Vérifier le type de fichier
                if (!file.type.match('image.*')) {
                    alert('Veuillez sélectionner une image');
                    return;
                }

                // Vérifier la taille (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('L\'image ne doit pas dépasser 5MB');
                    return;
                }

                // Afficher la prévisualisation
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    fileName.textContent = file.name;
                    dropZoneContent.classList.add('hidden');
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }

            // Supprimer l'image
            if (removeImageBtn) {
                removeImageBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    fileInput.value = '';
                    previewImg.src = '';
                    fileName.textContent = '';
                    dropZoneContent.classList.remove('hidden');
                    imagePreview.classList.add('hidden');
                });
            }

            // Gestion du drag and drop pour l'icône
            const iconeDropZone = document.getElementById('iconeDropZone');
            const iconeInput = document.getElementById('icone');
            const iconeDropContent = document.getElementById('iconeDropContent');
            const iconePreview = document.getElementById('iconePreview');
            const iconePreviewImg = document.getElementById('iconePreviewImg');
            const iconeFileName = document.getElementById('iconeFileName');
            const removeIconeBtn = document.getElementById('removeIcone');

            if (iconeDropZone && iconeInput) {
                // Empêcher les comportements par défaut
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    iconeDropZone.addEventListener(eventName, preventDefaults, false);
                });

                // Mettre en évidence la zone de drop
                ['dragenter', 'dragover'].forEach(eventName => {
                    iconeDropZone.addEventListener(eventName, function() {
                        iconeDropZone.style.borderColor = '#005792';
                        iconeDropZone.style.backgroundColor = '#E5F2FF';
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    iconeDropZone.addEventListener(eventName, function() {
                        iconeDropZone.style.borderColor = '#d1d5db';
                        iconeDropZone.style.backgroundColor = '#fafbfc';
                    }, false);
                });

                // Gérer le drop
                iconeDropZone.addEventListener('drop', function(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;

                    if (files.length > 0) {
                        iconeInput.files = files;
                        handleIconeFile(files[0]);
                    }
                }, false);

                // Gérer la sélection par clic
                iconeInput.addEventListener('change', function(e) {
                    if (this.files.length > 0) {
                        handleIconeFile(this.files[0]);
                    }
                });

                // Clic sur la zone
                iconeDropZone.addEventListener('click', function(e) {
                    if (!e.target.closest('#iconePreview')) {
                        iconeInput.click();
                    }
                });

                // Traiter le fichier icône
                function handleIconeFile(file) {
                    // Vérifier le type de fichier
                    if (!file.type.match('image.*')) {
                        alert('Veuillez sélectionner une image');
                        return;
                    }

                    // Vérifier la taille (2MB max)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('L\'icône ne doit pas dépasser 2MB');
                        return;
                    }

                    // Afficher la prévisualisation
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        iconePreviewImg.src = e.target.result;
                        iconeFileName.textContent = file.name;
                        iconeDropContent.classList.add('hidden');
                        iconePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }

                // Supprimer l'icône
                if (removeIconeBtn) {
                    removeIconeBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        iconeInput.value = '';
                        iconePreviewImg.src = '';
                        iconeFileName.textContent = '';
                        iconeDropContent.classList.remove('hidden');
                        iconePreview.classList.add('hidden');
                    });
                }
            }
        });

        // Afficher/masquer le champ placeholder selon la catégorie sélectionnée
        const categorieSelect = document.getElementById('categorie');
        const placeholderField = document.getElementById('placeholder_field');

        function togglePlaceholderField() {
            const selectedValue = categorieSelect.value;
            if (selectedValue === 's') {
                placeholderField.style.display = 'block';
            } else {
                placeholderField.style.display = 'none';
            }
        }

        // Vérifier au chargement de la page
        togglePlaceholderField();

        // Vérifier lors du changement de sélection
        categorieSelect.addEventListener('change', togglePlaceholderField);
    </script>
</body>

</html>
