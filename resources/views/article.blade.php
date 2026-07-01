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
    <title>Supriz - {{ isset($article) ? 'Modifier' : 'Publier' }} un article</title>

    <style>
        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background-color: #fff;
            font-size: 14px;
            color: #111827;
            transition: all .15s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: #005792;
            box-shadow: 0 0 0 3px rgba(0,87,146,.12);
        }
        .form-input:disabled {
            background-color: #f3f4f6;
            color: #6b7280;
        }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }
        .req { color: #ef4444; }
        .card-section {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 16px;
            border: 1px solid #e5e7eb;
        }
        @media (min-width: 768px) {
            .card-section { padding: 20px; }
        }
        .card-title {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .image-drop {
            position: relative;
            width: 100%;
            aspect-ratio: 1;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            background: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            overflow: hidden;
            transition: all .15s ease;
        }
        .image-drop:hover { border-color: #005792; background: #f0f7fc; }
        .image-drop.has-image { border-style: solid; border-color: #005792; }
        .image-drop input[type=file] {
            position: absolute; inset: 0; opacity: 0; cursor: pointer;
        }
        .image-drop img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .image-drop .placeholder {
            text-align: center; color: #9ca3af; font-size: 12px;
        }
        .image-drop .remove-btn {
            position: absolute; top: 8px; right: 8px;
            width: 28px; height: 28px;
            background: #ef4444; color: #fff;
            border-radius: 50%; display: none;
            align-items: center; justify-content: center;
            cursor: pointer; border: none; font-size: 16px;
            z-index: 10;
        }
        .image-drop.has-image .remove-btn { display: flex; }
        .image-drop.has-image .placeholder { display: none; }
        .radio-card {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 14px; border: 1.5px solid #e5e7eb;
            border-radius: 10px; cursor: pointer;
            background: #fff; transition: all .15s ease;
            font-size: 13px; font-weight: 500; color: #374151;
        }
        .radio-card:hover { border-color: #9ca3af; }
        .radio-card input { display: none; }
        .radio-card.selected {
            border-color: #005792; color: #005792;
            background: rgba(0,87,146,.06);
        }
        .switch {
            position: relative; width: 44px; height: 24px;
            background: #d1d5db; border-radius: 999px;
            transition: background .15s; cursor: pointer;
        }
        .switch::after {
            content: ''; position: absolute;
            top: 2px; left: 2px; width: 20px; height: 20px;
            background: #fff; border-radius: 50%;
            transition: transform .15s;
            box-shadow: 0 1px 3px rgba(0,0,0,.2);
        }
        .switch.on { background: #005792; }
        .switch.on::after { transform: translateX(20px); }
        .star-svg { width: 28px; height: 28px; cursor: pointer; }
        .badge-pill {
            display: inline-block;
            padding: 6px 12px;
            border: 2px solid #e5e7eb;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s;
            background: #fff;
        }
        .sticky-bar {
            position: sticky; bottom: 0;
            background: #fff;
            border-top: 1px solid #e5e7eb;
            padding: 12px 16px;
            margin: 20px -16px -16px;
            box-shadow: 0 -4px 12px rgba(0,0,0,.04);
            display: flex; gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        @media (min-width: 768px) {
            .sticky-bar { padding: 16px 20px; gap: 12px; flex-wrap: nowrap; }
        }
        .btn-primary, .btn-secondary { flex: 1 1 auto; justify-content: center; }
        @media (min-width: 640px) {
            .btn-primary, .btn-secondary { flex: 0 0 auto; }
        }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            background: #005792; color: #fff;
            padding: 10px 24px; border-radius: 10px;
            font-weight: 600; font-size: 14px;
            border: none; cursor: pointer;
            transition: all .15s;
        }
        .btn-primary:hover { background: #00457a; }
        .btn-primary:disabled { background: #9ca3af; cursor: not-allowed; }
        .btn-secondary {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff; color: #374151;
            padding: 10px 24px; border-radius: 10px;
            font-weight: 600; font-size: 14px;
            border: 1px solid #d1d5db; cursor: pointer;
        }
        .btn-secondary:hover { background: #f9fafb; }
    </style>
</head>


<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">
    @include('partials.gtm-body')

    @include('components.sidebar')

    <main class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20">

        {{-- Header --}}
        <div class="pt-4 md:pt-6 pb-0 px-4 md:px-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex items-center justify-between gap-3 flex-wrap mb-5">
                <img src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="" class="max-w-[120px] md:max-w-none h-auto">
                <h4 class="font-bold text-right text-white text-sm md:text-base">
                    {{ isset($article) ? 'Modifier un article' : 'Publier un article' }}
                </h4>
                <div class="w-10 hidden md:block"></div>
            </div>
        </div>

        {{-- Alerts --}}
        <div class="px-3 sm:px-4 md:px-8">
            @if ($errors->any())
                <div class="p-3 mb-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-2">
                    <i class="ri-error-warning-line text-red-500 text-lg"></i>
                    <p class="text-sm text-red-700">
                        Merci de corriger les erreurs indiquées ci-dessous en rouge.
                    </p>
                </div>
            @endif

            @if (session('success'))
                <div class="p-3 mb-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-3 mb-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form id="articleForm"
                  action="{{ isset($article) ? route('admin.article.update', $article->id) : route('admin.article.store') }}"
                  method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @if (isset($article))
                    @method('PUT')
                @endif

                {{-- Section 1: Informations principales --}}
                <div class="card-section">
                    <div class="card-title">
                        <i class="ri-information-line text-blue-600"></i>
                        Informations principales
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="form-label">Nom du produit <span class="req">*</span></label>
                            <input type="text" name="name_product"
                                   value="{{ old('name_product', $article->title ?? '') }}"
                                   class="form-input @error('name_product') !border-red-400 @enderror" placeholder="Ex: Bouquet de roses rouges"
                                   maxlength="255" required>
                            @error('name_product') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">Marchand <span class="req">*</span></label>
                            <select name="marchand_id" class="form-input @error('marchand_id') !border-red-400 @enderror" required>
                                <option value="">— Sélectionner un marchand —</option>
                                @foreach($marchands as $m)
                                    <option value="{{ $m->id }}"
                                        {{ old('marchand_id', $article->marchand_id ?? '') == $m->id ? 'selected' : '' }}>
                                        {{ $m->nom }}@if($m->ville) — {{ $m->ville }}@endif
                                    </option>
                                @endforeach
                            </select>
                            @error('marchand_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            @if($marchands->isEmpty())
                                <a href="{{ route('admin.marchand') }}" class="text-blue-600 text-xs mt-1 inline-block">
                                    Aucun marchand. Créez-en un d'abord.
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Prix (FCFA) <span class="req">*</span></label>
                            <input type="number" name="prix_product" min="0" step="100"
                                   value="{{ old('prix_product', $article->price ?? '') }}"
                                   class="form-input @error('prix_product') !border-red-400 @enderror" placeholder="0" required>
                            @error('prix_product') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">Prix promotionnel (FCFA)</label>
                            <input type="number" name="prix_promotion" min="0" step="100"
                                   value="{{ old('prix_promotion', $article->prix_promo ?? '') }}"
                                   class="form-input @error('prix_promotion') !border-red-400 @enderror" placeholder="Optionnel">
                            @error('prix_promotion') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            <p class="text-xs text-gray-500 mt-1">Doit être inférieur au prix normal</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">
                        <i class="ri-truck-line text-blue-500"></i>
                        Les frais de livraison sont calculés automatiquement selon la distance client-marchand.
                    </p>
                </div>

                {{-- Section 2: Catégorisation --}}
                <div class="card-section">
                    <div class="card-title">
                        <i class="ri-folder-line text-blue-600"></i>
                        Catégorisation
                    </div>

                    @php
                        $currentCat = isset($firstCategory)
                            ? $firstCategory->name_id_categories
                            : old('categorie_dhomme');
                    @endphp

                    <div class="mb-4">
                        <label class="form-label">Catégorie <span class="req">*</span></label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['h' => '👨 Homme', 'f' => '👩 Femme', 'e' => '🧒 Enfant'] as $code => $label)
                                <label class="radio-card {{ $currentCat === $code ? 'selected' : '' }}"
                                       data-cat="{{ $code }}">
                                    <input type="radio" name="categorie_dhomme" value="{{ $code }}"
                                           {{ $currentCat === $code ? 'checked' : '' }}
                                           onchange="onCategoryChange('{{ $code }}', this)">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Sous-catégorie <span class="req">*</span></label>
                        <select name="sousCategorieId" id="sous-categorie" class="form-input" required>
                            <option value="">— Sélectionnez d'abord une catégorie —</option>
                            @isset($article)
                                @foreach ($sousCategories as $sousCategorie)
                                    <option value="{{ $sousCategorie->id }}" selected>
                                        {{ $sousCategorie->name_sous_categories }}
                                    </option>
                                @endforeach
                                @foreach ($sousCategoriesNonAssociees as $sousCategorie)
                                    <option value="{{ $sousCategorie->id }}">
                                        {{ $sousCategorie->name_sous_categories }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    {{-- Badges --}}
                    @if(isset($badges))
                        @php
                            $selectedBadgeIds = isset($article)
                                ? $article->badges->pluck('id')->toArray()
                                : (old('badge_ids', []) ?: []);
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="form-label mb-0">Badges (max 5)</label>
                                <span class="text-xs text-gray-500">
                                    <span id="badge-count">{{ count($selectedBadgeIds) }}</span> / 5 sélectionnés
                                </span>
                            </div>
                            @if($badges->isEmpty())
                                <p class="text-sm text-gray-400">
                                    Aucun badge créé.
                                    <a href="{{ route('admin.badges') }}" class="text-blue-600 hover:underline">
                                        Créer un badge
                                    </a>
                                </p>
                            @else
                                <div class="flex flex-wrap gap-2" id="badges-wrapper">
                                    @foreach ($badges as $b)
                                        @php
                                            $c = $b->color ?? '#005792';
                                            $isSel = in_array($b->id, $selectedBadgeIds);
                                        @endphp
                                        <label class="cursor-pointer">
                                            <input type="checkbox"
                                                   name="badge_ids[]"
                                                   value="{{ $b->id }}"
                                                   class="badge-checkbox hidden"
                                                   data-color="{{ $c }}"
                                                   {{ $isSel ? 'checked' : '' }}>
                                            <span class="badge-pill"
                                                  style="color: {{ $c }}; background-color: {{ $isSel ? $c.'1F' : '#fff' }}; border-color: {{ $isSel ? $c : '#e5e7eb' }};">
                                                {{ $b->name }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Section 3: Détails --}}
                <div class="card-section">
                    <div class="card-title">
                        <i class="ri-file-text-line text-blue-600"></i>
                        Détails du produit
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label class="form-label">Durée de préparation <span class="req">*</span></label>
                            <input type="text" name="info"
                                   value="{{ old('info', $article->info ?? '') }}"
                                   class="form-input @error('info') !border-red-400 @enderror" placeholder="Ex: 24h, 48h, 2j" maxlength="255" required>
                            @error('info') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            <p class="text-xs text-gray-500 mt-1">Format: nombre + h (heures) ou j (jours)</p>
                        </div>

                        <div>
                            <label class="form-label">Évaluation</label>
                            <div class="flex items-center gap-1" id="stars-container">
                                @for ($i = 1; $i <= 5; $i++)
                                    @php $filled = isset($article) && $article->evaluation >= $i; @endphp
                                    <svg class="star-svg etoile" data-rating="{{ $i }}"
                                         viewBox="0 0 19 20" xmlns="http://www.w3.org/2000/svg"
                                         fill="{{ $filled ? '#F7A427' : 'none' }}">
                                        <path d="M7.12747 4.50667C8.18331 2.61334 8.7108 1.66667 9.49997 1.66667C10.2891 1.66667 10.8166 2.61334 11.8725 4.50667L12.1458 4.99667C12.4458 5.53501 12.5958 5.80417 12.8291 5.98167C13.0625 6.15917 13.3541 6.22501 13.9375 6.35667L14.4675 6.47667C16.5175 6.94084 17.5416 7.17251 17.7858 7.95667C18.0291 8.74001 17.3308 9.55751 15.9333 11.1917L15.5716 11.6142C15.175 12.0783 14.9758 12.3108 14.8866 12.5975C14.7975 12.885 14.8275 13.195 14.8875 13.8142L14.9425 14.3783C15.1533 16.5592 15.2591 17.6492 14.6208 18.1333C13.9825 18.6183 13.0225 18.1758 11.1041 17.2925L10.6066 17.0642C10.0616 16.8125 9.78914 16.6875 9.49997 16.6875C9.2108 16.6875 8.9383 16.8125 8.3933 17.0642L7.89664 17.2925C5.97747 18.1758 5.01747 18.6175 4.37997 18.1342C3.74081 17.6492 3.84664 16.5592 4.05747 14.3783L4.11247 13.815C4.17247 13.195 4.20247 12.885 4.11247 12.5983C4.02414 12.3108 3.82497 12.0783 3.42831 11.615L3.06664 11.1917C1.66914 9.55834 0.970805 8.74084 1.21414 7.95667C1.45831 7.17251 2.48331 6.94001 4.53331 6.47667L5.06331 6.35667C5.64581 6.22501 5.93664 6.15917 6.17081 5.98167C6.40414 5.80417 6.55414 5.53501 6.85414 4.99667L7.12747 4.50667Z"
                                            stroke="#F7A427" stroke-width="1.5"/>
                                    </svg>
                                @endfor
                                <input type="hidden" name="evaluation"
                                       value="{{ old('evaluation', $article->evaluation ?? '') }}"
                                       id="nbr-etoile">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Description <span class="req">*</span></label>
                        <textarea name="description" class="form-input" rows="4"
                                  placeholder="Décrivez le produit en détail..." required>{{ old('description', $article->description ?? '') }}</textarea>
                    </div>

                    {{-- Personnalisation switch --}}
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="switch {{ isset($article) && $article->personnalisable === 'yes' ? 'on' : '' }}"
                             id="persoSwitch" onclick="togglePerso()"></div>
                        <input type="checkbox" name="personnalisation" id="persoCheckbox" value="on"
                               class="hidden"
                               {{ isset($article) && $article->personnalisable === 'yes' ? 'checked' : '' }}>
                        <div>
                            <div class="font-medium text-sm text-gray-800">Produit personnalisable</div>
                            <div class="text-xs text-gray-500">Le client pourra ajouter un message ou une demande spéciale</div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Images --}}
                <div class="card-section">
                    <div class="card-title">
                        <i class="ri-image-line text-blue-600"></i>
                        Images du produit
                    </div>
                    <p class="text-xs text-gray-500 mb-3">Formats acceptés: JPG, PNG, GIF — Max 2 MB par image</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-md">
                        @php
                            $img1Url = isset($article) && $article->photo_main
                                ? asset('storage/'.$article->photo_main) : null;
                            $img2Url = isset($article) && $article->photo_secondary
                                ? asset('storage/'.$article->photo_secondary) : null;
                        @endphp

                        <div class="image-drop {{ $img1Url ? 'has-image' : '' }}" id="drop-1">
                            <input type="file" name="img1" id="imgInput1" accept="image/*"
                                   {{ isset($article) ? '' : 'required' }}>
                            <img id="imgPreview1" src="{{ $img1Url ?? '' }}"
                                 style="{{ $img1Url ? '' : 'display:none;' }}" alt="">
                            <div class="placeholder" {{ $img1Url ? 'style=display:none;' : '' }}>
                                <i class="ri-add-circle-line text-3xl block mb-1"></i>
                                Image principale<br>
                                <span class="text-blue-600">Cliquer pour ajouter</span>
                            </div>
                            <button type="button" class="remove-btn" onclick="resetImage(1, event)">×</button>
                        </div>

                        <div class="image-drop {{ $img2Url ? 'has-image' : '' }}" id="drop-2">
                            <input type="file" name="img2" id="imgInput2" accept="image/*">
                            <img id="imgPreview2" src="{{ $img2Url ?? '' }}"
                                 style="{{ $img2Url ? '' : 'display:none;' }}" alt="">
                            <div class="placeholder" {{ $img2Url ? 'style=display:none;' : '' }}>
                                <i class="ri-add-circle-line text-3xl block mb-1"></i>
                                Image secondaire<br>
                                <span class="text-blue-600">Optionnel</span>
                            </div>
                            <button type="button" class="remove-btn" onclick="resetImage(2, event)">×</button>
                        </div>
                    </div>
                </div>

                {{-- Sticky action bar --}}
                <div class="sticky-bar">
                    <a href="{{ url()->previous() }}" class="btn-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn-primary" id="submitBtn">
                        <span id="submitText">
                            {{ isset($article) ? 'Mettre à jour' : 'Publier l\'article' }}
                        </span>
                        <i class="ri-arrow-right-line"></i>
                    </button>
                </div>
            </form>

            {{-- Récentes publications --}}
            <h3 class="mt-8 mb-3 font-semibold text-gray-700">Publications récentes</h3>
            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
                @if($produits->isEmpty())
                    <p class="p-6 text-center text-gray-400">Aucune publication.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px]">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                            <tr>
                                <th class="px-4 py-3 text-left">Image</th>
                                <th class="px-4 py-3 text-left">Nom</th>
                                <th class="px-4 py-3 text-left">Prix</th>
                                <th class="px-4 py-3 text-left">Marchand</th>
                                <th class="px-4 py-3 text-left">Note</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produits as $item)
                                <tr class="border-t border-gray-100">
                                    <td class="px-4 py-3">
                                        <img class="w-12 h-12 rounded object-cover"
                                             src="{{ asset('storage/' . $item->photo_main) }}" alt="">
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-700">
                                        {{ $item->title }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ number_format($item->price, 0, ',', ' ') }} F
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        {{ $item->entreprise }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 19 20"
                                                     fill="{{ $item->evaluation >= $i ? '#F6A427' : 'none' }}"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M7.12747 4.50667C8.18331 2.61334 8.7108 1.66667 9.49997 1.66667C10.2891 1.66667 10.8166 2.61334 11.8725 4.50667L12.1458 4.99667C12.4458 5.53501 12.5958 5.80417 12.8291 5.98167C13.0625 6.15917 13.3541 6.22501 13.9375 6.35667L14.4675 6.47667C16.5175 6.94084 17.5416 7.17251 17.7858 7.95667C18.0291 8.74001 17.3308 9.55751 15.9333 11.1917L15.5716 11.6142C15.175 12.0783 14.9758 12.3108 14.8866 12.5975C14.7975 12.885 14.8275 13.195 14.8875 13.8142L14.9425 14.3783C15.1533 16.5592 15.2591 17.6492 14.6208 18.1333C13.9825 18.6183 13.0225 18.1758 11.1041 17.2925L10.6066 17.0642C10.0616 16.8125 9.78914 16.6875 9.49997 16.6875C9.2108 16.6875 8.9383 16.8125 8.3933 17.0642L7.89664 17.2925C5.97747 18.1758 5.01747 18.6175 4.37997 18.1342C3.74081 17.6492 3.84664 16.5592 4.05747 14.3783L4.11247 13.815C4.17247 13.195 4.20247 12.885 4.11247 12.5983C4.02414 12.3108 3.82497 12.0783 3.42831 11.615L3.06664 11.1917C1.66914 9.55834 0.970805 8.74084 1.21414 7.95667C1.45831 7.17251 2.48331 6.94001 4.53331 6.47667L5.06331 6.35667C5.64581 6.22501 5.93664 6.15917 6.17081 5.98167C6.40414 5.80417 6.55414 5.53501 6.85414 4.99667L7.12747 4.50667Z"
                                                        stroke="#F7A427" stroke-width="1.5"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('admin.article.edit', $item->id) }}"
                                           class="text-blue-600 hover:underline text-sm font-medium">
                                            Modifier
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        // --- Catégorie / sous-catégorie ---
        function onCategoryChange(cat, input) {
            // Visual selection
            document.querySelectorAll('.radio-card').forEach(c => c.classList.remove('selected'));
            input.closest('.radio-card').classList.add('selected');
            // Load subcats
            loadSousCategories(cat);
        }

        function loadSousCategories(categorie) {
            const select = document.getElementById('sous-categorie');
            select.innerHTML = '<option value="">Chargement…</option>';
            $.ajax({
                url: "{{ url('/admin/get-sous-categories') }}",
                type: 'POST',
                data: {
                    categorie: categorie,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    select.innerHTML = '<option value="">— Sélectionnez une sous-catégorie —</option>';
                    response.forEach(function(sc) {
                        const opt = document.createElement('option');
                        opt.value = sc.id;
                        opt.textContent = sc.name;
                        select.appendChild(opt);
                    });
                },
                error: function() {
                    select.innerHTML = '<option value="">Erreur de chargement</option>';
                }
            });
        }

        // --- Personnalisation switch ---
        function togglePerso() {
            const sw = document.getElementById('persoSwitch');
            const cb = document.getElementById('persoCheckbox');
            cb.checked = !cb.checked;
            sw.classList.toggle('on', cb.checked);
        }

        // --- Stars rating ---
        const stars = document.querySelectorAll('#stars-container .etoile');
        const ratingInput = document.getElementById('nbr-etoile');
        let lockedRating = parseInt(ratingInput.value || '0');

        function paintStars(n) {
            stars.forEach((s, i) => {
                s.setAttribute('fill', i < n ? '#F7A427' : 'none');
            });
        }

        stars.forEach((star, idx) => {
            star.addEventListener('mouseenter', () => paintStars(idx + 1));
            star.addEventListener('mouseleave', () => paintStars(lockedRating));
            star.addEventListener('click', () => {
                lockedRating = idx + 1;
                ratingInput.value = lockedRating;
                paintStars(lockedRating);
            });
        });

        // --- Images upload ---
        function setupImageDrop(num) {
            const drop = document.getElementById('drop-' + num);
            const input = document.getElementById('imgInput' + num);
            const preview = document.getElementById('imgPreview' + num);

            input.addEventListener('change', (e) => {
                const file = e.target.files && e.target.files[0];
                if (!file) return;
                // Validation taille (2 MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Image trop volumineuse (max 2 MB)');
                    input.value = '';
                    return;
                }
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
                drop.classList.add('has-image');
            });
        }
        setupImageDrop(1);
        setupImageDrop(2);

        function resetImage(num, event) {
            event.stopPropagation();
            const drop = document.getElementById('drop-' + num);
            const input = document.getElementById('imgInput' + num);
            const preview = document.getElementById('imgPreview' + num);
            input.value = '';
            preview.src = '';
            preview.style.display = 'none';
            drop.classList.remove('has-image');
        }

        // --- Badges ---
        const badgeWrapper = document.getElementById('badges-wrapper');
        const badgeCounter = document.getElementById('badge-count');
        if (badgeWrapper) {
            badgeWrapper.addEventListener('change', (e) => {
                const t = e.target;
                if (!t.classList.contains('badge-checkbox')) return;
                const checked = badgeWrapper.querySelectorAll('.badge-checkbox:checked');
                if (checked.length > 5) {
                    t.checked = false;
                    alert('Maximum 5 badges par produit.');
                    return;
                }
                badgeWrapper.querySelectorAll('.badge-checkbox').forEach(cb => {
                    const c = cb.dataset.color || '#005792';
                    const span = cb.nextElementSibling;
                    if (cb.checked) {
                        span.style.backgroundColor = c + '1F';
                        span.style.borderColor = c;
                    } else {
                        span.style.backgroundColor = '#fff';
                        span.style.borderColor = '#e5e7eb';
                    }
                });
                badgeCounter.textContent = badgeWrapper.querySelectorAll('.badge-checkbox:checked').length;
            });
        }

        // --- Form submit: validation + loading ---
        const form = document.getElementById('articleForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');

        form.addEventListener('submit', function(e) {
            // Vérif prix promo < prix
            const prix = parseFloat(form.prix_product.value || '0');
            const promo = parseFloat(form.prix_promotion.value || '0');
            if (promo > 0 && promo >= prix) {
                e.preventDefault();
                alert('Le prix promotionnel doit être strictement inférieur au prix normal.');
                return;
            }
            // Vérif catégorie sélectionnée
            if (!form.categorie_dhomme.value) {
                e.preventDefault();
                alert('Veuillez sélectionner une catégorie.');
                return;
            }
            // Vérif sous-catégorie
            if (!form.sousCategorieId.value) {
                e.preventDefault();
                alert('Veuillez sélectionner une sous-catégorie.');
                return;
            }
            // Lock submit
            submitBtn.disabled = true;
            submitText.textContent = 'Envoi en cours…';
        });
    </script>

    <script src="https://unpkg.com/@popperjs/core@2"></script>
</body>

</html>
