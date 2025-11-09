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
    <title>Supriz - Articles </title>
</head>


<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">

    <!-- start: Sidebar -->
    @include('components.sidebar')
    <!-- end: Sidebar -->

    <!-- start: Main -->
    <main
        class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20 ">

        <div class="pt-6 pb-0 pl-6 pr-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex items-center justify-between mb-5">
                <img src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="">
                <h4 class="font-bold text-right text-white">Publier un article</h4>
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
        @if ($errors->any())
            <div class="bg-red-500 text-center p-2 rounded text-white">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-500 text-center p-2 rounded text-white">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        <form action="{{ isset($article) ? route('article.update', $article->id) : route('article.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($article))
                @method('PUT')
            @endif

            <h1 class="mt-4 mb-3 font-bold text-color-design f-20">
                {{ isset($article) ? 'Modifier un article' : 'Publier un article' }}
            </h1>
            <p class="mb-3">Informations sur l’article</p>

            <div class="flex items-center justify-between w-full mb-4">
                <div class="grid w-full mr-3">
                    <span class="text-gray-400">Nom</span>
                    <input type="text" name="name_product" value="{{ isset($article) ? $article->title : '' }}"
                        class="rounded">
                </div>

                <div class="grid w-full mr-3">
                    <span class="text-gray-400">Prix</span>
                    <input type="text" name="prix_product" value="{{ isset($article) ? $article->price : '' }}"
                        class="rounded">
                </div>

                <div class="grid w-full">
                    <span class="text-gray-400">Entreprise</span>
                    <input type="text" name="entreprise_product"
                        value="{{ isset($article) ? $article->entreprise : '' }}" class="rounded">
                </div>
            </div>

            <div class="flex items-center justify-between w-full mb-4">
                <div class="flex items-center justify-between w-1/2 mt-34 ">
                    <div class="top-0 grid w-full">
                        <span class="mb-1 text-gray-400">Personalisation</span>
                        <input type="checkbox" name="personnalisation"
                            {{ isset($article) && $article->personnalisable==='yes' ? 'checked' : '' }} class="rounded">
                    </div>

                    <div class="right-0 grid float-right mr-3">
                        <span class="text-gray-400">Catégories</span>
                        <div class="flex items-center justify-between w-10 radio-column">
                            <input type="radio"
                                {{ isset($article) && $firstCategory->name_id_categories === 'h' ? 'checked' : '' }}
                                name="categorie_dhomme" value="h"
                                {{ isset($article) && $article->categorie_dhomme === 'h' ? 'checked' : '' }}
                                onchange="loadSousCategories('h')" class="mr-2">
                            <span>Homme</span>
                        </div>

                        <div class="flex items-center justify-between w-10 radio-column">
                            <input type="radio" name="categorie_dhomme" value="f"
                                {{ isset($article) && $firstCategory->name_id_categories === 'f' ? 'checked' : '' }}
                                onchange="loadSousCategories('f')" class="mr-2">
                            <span>Femme</span>
                        </div>

                        <div class="flex items-center justify-between w-10 radio-column">
                            <input type="radio" name="categorie_dhomme" value="e"
                                {{ isset($article) && $firstCategory->name_id_categories === 'e' ? 'checked' : '' }}
                                onchange="loadSousCategories('e')" class="mr-2">
                            <span>Enfant</span>
                        </div>
                    </div>
                </div>

                <div class="grid w-1/2 mr-3">
                    <span class="mb-2 text-gray-400">Sous-Catégories</span>
                    <select name="sousCategorieId" id="sous-categorie" class="p-1 rounded">
                        <option value="none">Sélectionnez une Sous-Catégorie</option>
                        @isset($article)
                            @foreach ($sousCategories as $sousCategorie)
                                <option value="{{ $sousCategorie->id }}" selected
                                    @if ($article->sousCategorieId == $sousCategorie->id) selected @endif>
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





                    <span class="mt-3 mb-2 text-gray-400">Entrer une évaluation</span>

                    <div class="flex">
                        <svg width="19" class="mr-2 cursor-pointer etoile" height="20" viewBox="0 0 19 20"
                            fill="{{ isset($article) && $article->evaluation >= 1 ? '#F7A427' : 'none' }}"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7.12747 4.50667C8.18331 2.61334 8.7108 1.66667 9.49997 1.66667C10.2891 1.66667 10.8166 2.61334 11.8725 4.50667L12.1458 4.99667C12.4458 5.53501 12.5958 5.80417 12.8291 5.98167C13.0625 6.15917 13.3541 6.22501 13.9375 6.35667L14.4675 6.47667C16.5175 6.94084 17.5416 7.17251 17.7858 7.95667C18.0291 8.74001 17.3308 9.55751 15.9333 11.1917L15.5716 11.6142C15.175 12.0783 14.9758 12.3108 14.8866 12.5975C14.7975 12.885 14.8275 13.195 14.8875 13.8142L14.9425 14.3783C15.1533 16.5592 15.2591 17.6492 14.6208 18.1333C13.9825 18.6183 13.0225 18.1758 11.1041 17.2925L10.6066 17.0642C10.0616 16.8125 9.78914 16.6875 9.49997 16.6875C9.2108 16.6875 8.9383 16.8125 8.3933 17.0642L7.89664 17.2925C5.97747 18.1758 5.01747 18.6175 4.37997 18.1342C3.74081 17.6492 3.84664 16.5592 4.05747 14.3783L4.11247 13.815C4.17247 13.195 4.20247 12.885 4.11247 12.5983C4.02414 12.3108 3.82497 12.0783 3.42831 11.615L3.06664 11.1917C1.66914 9.55834 0.970805 8.74084 1.21414 7.95667C1.45831 7.17251 2.48331 6.94001 4.53331 6.47667L5.06331 6.35667C5.64581 6.22501 5.93664 6.15917 6.17081 5.98167C6.40414 5.80417 6.55414 5.53501 6.85414 4.99667L7.12747 4.50667Z"
                                stroke="#F7A427" stroke-width="1.5" />
                        </svg>

                        <svg width="19" class="mr-2 cursor-pointer etoile" height="20" viewBox="0 0 19 20"
                            fill="{{ isset($article) && $article->evaluation >= 2 ? '#F7A427' : 'none' }}"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7.12747 4.50667C8.18331 2.61334 8.7108 1.66667 9.49997 1.66667C10.2891 1.66667 10.8166 2.61334 11.8725 4.50667L12.1458 4.99667C12.4458 5.53501 12.5958 5.80417 12.8291 5.98167C13.0625 6.15917 13.3541 6.22501 13.9375 6.35667L14.4675 6.47667C16.5175 6.94084 17.5416 7.17251 17.7858 7.95667C18.0291 8.74001 17.3308 9.55751 15.9333 11.1917L15.5716 11.6142C15.175 12.0783 14.9758 12.3108 14.8866 12.5975C14.7975 12.885 14.8275 13.195 14.8875 13.8142L14.9425 14.3783C15.1533 16.5592 15.2591 17.6492 14.6208 18.1333C13.9825 18.6183 13.0225 18.1758 11.1041 17.2925L10.6066 17.0642C10.0616 16.8125 9.78914 16.6875 9.49997 16.6875C9.2108 16.6875 8.9383 16.8125 8.3933 17.0642L7.89664 17.2925C5.97747 18.1758 5.01747 18.6175 4.37997 18.1342C3.74081 17.6492 3.84664 16.5592 4.05747 14.3783L4.11247 13.815C4.17247 13.195 4.20247 12.885 4.11247 12.5983C4.02414 12.3108 3.82497 12.0783 3.42831 11.615L3.06664 11.1917C1.66914 9.55834 0.970805 8.74084 1.21414 7.95667C1.45831 7.17251 2.48331 6.94001 4.53331 6.47667L5.06331 6.35667C5.64581 6.22501 5.93664 6.15917 6.17081 5.98167C6.40414 5.80417 6.55414 5.53501 6.85414 4.99667L7.12747 4.50667Z"
                                stroke="#F7A427" stroke-width="1.5" />
                        </svg>

                        <svg width="19" class="mr-2 cursor-pointer etoile" height="20" viewBox="0 0 19 20"
                            fill="{{ isset($article) && $article->evaluation >= 3 ? '#F7A427' : 'none' }}"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7.12747 4.50667C8.18331 2.61334 8.7108 1.66667 9.49997 1.66667C10.2891 1.66667 10.8166 2.61334 11.8725 4.50667L12.1458 4.99667C12.4458 5.53501 12.5958 5.80417 12.8291 5.98167C13.0625 6.15917 13.3541 6.22501 13.9375 6.35667L14.4675 6.47667C16.5175 6.94084 17.5416 7.17251 17.7858 7.95667C18.0291 8.74001 17.3308 9.55751 15.9333 11.1917L15.5716 11.6142C15.175 12.0783 14.9758 12.3108 14.8866 12.5975C14.7975 12.885 14.8275 13.195 14.8875 13.8142L14.9425 14.3783C15.1533 16.5592 15.2591 17.6492 14.6208 18.1333C13.9825 18.6183 13.0225 18.1758 11.1041 17.2925L10.6066 17.0642C10.0616 16.8125 9.78914 16.6875 9.49997 16.6875C9.2108 16.6875 8.9383 16.8125 8.3933 17.0642L7.89664 17.2925C5.97747 18.1758 5.01747 18.6175 4.37997 18.1342C3.74081 17.6492 3.84664 16.5592 4.05747 14.3783L4.11247 13.815C4.17247 13.195 4.20247 12.885 4.11247 12.5983C4.02414 12.3108 3.82497 12.0783 3.42831 11.615L3.06664 11.1917C1.66914 9.55834 0.970805 8.74084 1.21414 7.95667C1.45831 7.17251 2.48331 6.94001 4.53331 6.47667L5.06331 6.35667C5.64581 6.22501 5.93664 6.15917 6.17081 5.98167C6.40414 5.80417 6.55414 5.53501 6.85414 4.99667L7.12747 4.50667Z"
                                stroke="#F7A427" stroke-width="1.5" />
                        </svg>

                        <svg width="19" class="mr-2 cursor-pointer etoile" height="20" viewBox="0 0 19 20"
                            fill="{{ isset($article) && $article->evaluation >= 4 ? '#F7A427' : 'none' }}"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7.12747 4.50667C8.18331 2.61334 8.7108 1.66667 9.49997 1.66667C10.2891 1.66667 10.8166 2.61334 11.8725 4.50667L12.1458 4.99667C12.4458 5.53501 12.5958 5.80417 12.8291 5.98167C13.0625 6.15917 13.3541 6.22501 13.9375 6.35667L14.4675 6.47667C16.5175 6.94084 17.5416 7.17251 17.7858 7.95667C18.0291 8.74001 17.3308 9.55751 15.9333 11.1917L15.5716 11.6142C15.175 12.0783 14.9758 12.3108 14.8866 12.5975C14.7975 12.885 14.8275 13.195 14.8875 13.8142L14.9425 14.3783C15.1533 16.5592 15.2591 17.6492 14.6208 18.1333C13.9825 18.6183 13.0225 18.1758 11.1041 17.2925L10.6066 17.0642C10.0616 16.8125 9.78914 16.6875 9.49997 16.6875C9.2108 16.6875 8.9383 16.8125 8.3933 17.0642L7.89664 17.2925C5.97747 18.1758 5.01747 18.6175 4.37997 18.1342C3.74081 17.6492 3.84664 16.5592 4.05747 14.3783L4.11247 13.815C4.17247 13.195 4.20247 12.885 4.11247 12.5983C4.02414 12.3108 3.82497 12.0783 3.42831 11.615L3.06664 11.1917C1.66914 9.55834 0.970805 8.74084 1.21414 7.95667C1.45831 7.17251 2.48331 6.94001 4.53331 6.47667L5.06331 6.35667C5.64581 6.22501 5.93664 6.15917 6.17081 5.98167C6.40414 5.80417 6.55414 5.53501 6.85414 4.99667L7.12747 4.50667Z"
                                stroke="#F7A427" stroke-width="1.5" />
                        </svg>

                        <svg width="19" class="mr-2 cursor-pointer etoile" height="20" viewBox="0 0 19 20"
                            fill="{{ isset($article) && $article->evaluation >= 5 ? '#F7A427' : 'none' }}"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7.12747 4.50667C8.18331 2.61334 8.7108 1.66667 9.49997 1.66667C10.2891 1.66667 10.8166 2.61334 11.8725 4.50667L12.1458 4.99667C12.4458 5.53501 12.5958 5.80417 12.8291 5.98167C13.0625 6.15917 13.3541 6.22501 13.9375 6.35667L14.4675 6.47667C16.5175 6.94084 17.5416 7.17251 17.7858 7.95667C18.0291 8.74001 17.3308 9.55751 15.9333 11.1917L15.5716 11.6142C15.175 12.0783 14.9758 12.3108 14.8866 12.5975C14.7975 12.885 14.8275 13.195 14.8875 13.8142L14.9425 14.3783C15.1533 16.5592 15.2591 17.6492 14.6208 18.1333C13.9825 18.6183 13.0225 18.1758 11.1041 17.2925L10.6066 17.0642C10.0616 16.8125 9.78914 16.6875 9.49997 16.6875C9.2108 16.6875 8.9383 16.8125 8.3933 17.0642L7.89664 17.2925C5.97747 18.1758 5.01747 18.6175 4.37997 18.1342C3.74081 17.6492 3.84664 16.5592 4.05747 14.3783L4.11247 13.815C4.17247 13.195 4.20247 12.885 4.11247 12.5983C4.02414 12.3108 3.82497 12.0783 3.42831 11.615L3.06664 11.1917C1.66914 9.55834 0.970805 8.74084 1.21414 7.95667C1.45831 7.17251 2.48331 6.94001 4.53331 6.47667L5.06331 6.35667C5.64581 6.22501 5.93664 6.15917 6.17081 5.98167C6.40414 5.80417 6.55414 5.53501 6.85414 4.99667L7.12747 4.50667Z"
                                stroke="#F7A427" stroke-width="1.5" />
                        </svg>
                        <input type="hidden" name="evaluation" value="{{ isset($article) ? $article->evaluation : '' }}" id="nbr-etoile">
                    </div>

                </div>

                <div class="grid w-1/2 mt-64">
                    <span class="mb-2 text-gray-400">Durée</span>
                    <input type="text" name="info" value="{{ isset($article) ? $article->info : '' }}"
                        class="rounded">
                </div>



            </div>

            <div class="flex">
                <div class="grid w-full mr-3">
                    <span class="text-gray-400">Prix promotionnel</span>
                    <input type="text" value="{{ isset($article) ? $article->prix_promo : '' }}"
                        name="prix_promotion" class="rounded">
                </div>

                <div class="grid w-full">
                    <span class="text-gray-400">Prix de livraison</span>
                    <input type="text" value="{{ isset($article) ? $article->prix_livraison : '' }}"
                        class="rounded" name="prix_livraison">
                </div>
            </div>

            <div class="flex w-full">

                <div class="grid w-1/2 mr-4">
                    <span class="text-gray-400">Description du produit</span>
                    <textarea name="description" id="" class="p-3 rounded resize-none h-70" style="height: 132px;"
                        cols="30" rows="1">{{ isset($article) ? $article->description : '' }}</textarea>
                </div>
                <div class="grid w-1/2">
                    <span class="text-gray-400">Choisir l’image de l’article</span>
                    <div class="flex">
                        <div class="mr-3">
                            <div class="h-input-file relative">
                                <img src="{{ asset('/images/svg/streamline_delete-1-solid.svg') }}"
                                    class="absolute costum_close removeimage hidden right-0 float-right cursor-pointer text-end "
                                    alt="">
                                <img src="{{ isset($article) && $article->photo_main ? asset('storage/' . $article->photo_main) : asset('images/svg/group_10.svg') }}" id="imagePreview"
                                    class="mt-3 cursor-pointer h-input-file" alt="">
                                <input type="file" id="imageInput" accept="image/*" name="img1"
                                    class="relative opacity-0 cursor-pointer h-input-file -t-15">
                            </div>
                        </div>

                        <div class="mr-3">
                            <div class="h-input-file relative">
                                <img src="{{ asset('/images/svg/streamline_delete-1-solid.svg') }}"
                                    class="absolute costum_close removeimage hidden right-0 float-right cursor-pointer text-end "
                                    alt="">
                                <img src="{{ isset($article) && $article->photo_secondary ? asset('storage/' . $article->photo_secondary) : asset('images/svg/group_10.svg') }}"
                                    id="imagePreview" class="mt-3 cursor-pointer h-input-file" alt="">
                                <input type="file" id="imageInput" accept="image/*" name="img2"
                                    class="relative opacity-0 cursor-pointer h-input-file -t-15">
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <button type="submit"
                class="relative w-full p-2 mt-2 mb-3 text-white rounded cursor-pointer btn-design">{{ isset($article) ? 'Mettre à jour' : 'Publier' }}</button>
        </form>
        <p class="mb-2 font-light text-black">Les récentes publications</p>
        <div
            class="p-3 overflow-x-auto bg-white border border-gray-100 shadow-md rounded-20 shadow-black/5 lg:col-span-2">
            <table class="w-full">

                <tbody>

                    @foreach ($produits as $item)
                        <!-- Continue this pattern for the rest of the rows -->
                        <tr>
                            <td class="w-20 px-4 py-2 border-b border-b-gray-50">
                                <span class="text-[13px] font-medium text-gray-400">
                                    <img class="img-produit" src="{{ asset('storage/' . $item->photo_main) }}"
                                        alt="">
                                </span>
                            </td>
                            <td class="px-4 py-2 border-b w-120 border-b-gray-50">
                                <span class="text-[13px] font-medium text-gray-400">{{ $item->title }}</span>
                            </td>
                            <td class="w-40 px-4 py-2 border-b border-b-gray-50">
                                <span class="text-[13px] font-medium text-gray-400">{{ $item->price }}</span>
                            </td>
                            <td class="relative px-4 py-2 border-b left-40 w-80 border-b-gray-50">
                                <span
                                    class="text-[13px] font-medium text-gray-400 -left-15 relative">{{ $item->entreprise }}</span>
                            </td>
                            <td class="flex px-4 py-2 ">
                                <div class="flex">
                                    <svg width="19" class="mr-2 cursor-pointer minim-svg" height="20"
                                        viewBox="0 0 19 20"
                                        fill="@if ($item->evaluation >= 1) #F6A427 @else none @endif"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.12747 4.50667C8.18331 2.61334 8.7108 1.66667 9.49997 1.66667C10.2891 1.66667 10.8166 2.61334 11.8725 4.50667L12.1458 4.99667C12.4458 5.53501 12.5958 5.80417 12.8291 5.98167C13.0625 6.15917 13.3541 6.22501 13.9375 6.35667L14.4675 6.47667C16.5175 6.94084 17.5416 7.17251 17.7858 7.95667C18.0291 8.74001 17.3308 9.55751 15.9333 11.1917L15.5716 11.6142C15.175 12.0783 14.9758 12.3108 14.8866 12.5975C14.7975 12.885 14.8275 13.195 14.8875 13.8142L14.9425 14.3783C15.1533 16.5592 15.2591 17.6492 14.6208 18.1333C13.9825 18.6183 13.0225 18.1758 11.1041 17.2925L10.6066 17.0642C10.0616 16.8125 9.78914 16.6875 9.49997 16.6875C9.2108 16.6875 8.9383 16.8125 8.3933 17.0642L7.89664 17.2925C5.97747 18.1758 5.01747 18.6175 4.37997 18.1342C3.74081 17.6492 3.84664 16.5592 4.05747 14.3783L4.11247 13.815C4.17247 13.195 4.20247 12.885 4.11247 12.5983C4.02414 12.3108 3.82497 12.0783 3.42831 11.615L3.06664 11.1917C1.66914 9.55834 0.970805 8.74084 1.21414 7.95667C1.45831 7.17251 2.48331 6.94001 4.53331 6.47667L5.06331 6.35667C5.64581 6.22501 5.93664 6.15917 6.17081 5.98167C6.40414 5.80417 6.55414 5.53501 6.85414 4.99667L7.12747 4.50667Z"
                                            stroke="#F7A427" stroke-width="1.5" />
                                    </svg>

                                    <svg width="19" class="mr-2 cursor-pointer minim-svg" height="20"
                                        viewBox="0 0 19 20"
                                        fill="@if ($item->evaluation >= 2) #F6A427 @else none @endif"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.12747 4.50667C8.18331 2.61334 8.7108 1.66667 9.49997 1.66667C10.2891 1.66667 10.8166 2.61334 11.8725 4.50667L12.1458 4.99667C12.4458 5.53501 12.5958 5.80417 12.8291 5.98167C13.0625 6.15917 13.3541 6.22501 13.9375 6.35667L14.4675 6.47667C16.5175 6.94084 17.5416 7.17251 17.7858 7.95667C18.0291 8.74001 17.3308 9.55751 15.9333 11.1917L15.5716 11.6142C15.175 12.0783 14.9758 12.3108 14.8866 12.5975C14.7975 12.885 14.8275 13.195 14.8875 13.8142L14.9425 14.3783C15.1533 16.5592 15.2591 17.6492 14.6208 18.1333C13.9825 18.6183 13.0225 18.1758 11.1041 17.2925L10.6066 17.0642C10.0616 16.8125 9.78914 16.6875 9.49997 16.6875C9.2108 16.6875 8.9383 16.8125 8.3933 17.0642L7.89664 17.2925C5.97747 18.1758 5.01747 18.6175 4.37997 18.1342C3.74081 17.6492 3.84664 16.5592 4.05747 14.3783L4.11247 13.815C4.17247 13.195 4.20247 12.885 4.11247 12.5983C4.02414 12.3108 3.82497 12.0783 3.42831 11.615L3.06664 11.1917C1.66914 9.55834 0.970805 8.74084 1.21414 7.95667C1.45831 7.17251 2.48331 6.94001 4.53331 6.47667L5.06331 6.35667C5.64581 6.22501 5.93664 6.15917 6.17081 5.98167C6.40414 5.80417 6.55414 5.53501 6.85414 4.99667L7.12747 4.50667Z"
                                            stroke="#F7A427" stroke-width="1.5" />
                                    </svg>

                                    <svg width="19" class="mr-2 cursor-pointer minim-svg" height="20"
                                        viewBox="0 0 19 20"
                                        fill="@if ($item->evaluation >= 3) #F6A427 @else none @endif"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.12747 4.50667C8.18331 2.61334 8.7108 1.66667 9.49997 1.66667C10.2891 1.66667 10.8166 2.61334 11.8725 4.50667L12.1458 4.99667C12.4458 5.53501 12.5958 5.80417 12.8291 5.98167C13.0625 6.15917 13.3541 6.22501 13.9375 6.35667L14.4675 6.47667C16.5175 6.94084 17.5416 7.17251 17.7858 7.95667C18.0291 8.74001 17.3308 9.55751 15.9333 11.1917L15.5716 11.6142C15.175 12.0783 14.9758 12.3108 14.8866 12.5975C14.7975 12.885 14.8275 13.195 14.8875 13.8142L14.9425 14.3783C15.1533 16.5592 15.2591 17.6492 14.6208 18.1333C13.9825 18.6183 13.0225 18.1758 11.1041 17.2925L10.6066 17.0642C10.0616 16.8125 9.78914 16.6875 9.49997 16.6875C9.2108 16.6875 8.9383 16.8125 8.3933 17.0642L7.89664 17.2925C5.97747 18.1758 5.01747 18.6175 4.37997 18.1342C3.74081 17.6492 3.84664 16.5592 4.05747 14.3783L4.11247 13.815C4.17247 13.195 4.20247 12.885 4.11247 12.5983C4.02414 12.3108 3.82497 12.0783 3.42831 11.615L3.06664 11.1917C1.66914 9.55834 0.970805 8.74084 1.21414 7.95667C1.45831 7.17251 2.48331 6.94001 4.53331 6.47667L5.06331 6.35667C5.64581 6.22501 5.93664 6.15917 6.17081 5.98167C6.40414 5.80417 6.55414 5.53501 6.85414 4.99667L7.12747 4.50667Z"
                                            stroke="#F7A427" stroke-width="1.5" />
                                    </svg>

                                    <svg width="19" class="mr-2 cursor-pointer minim-svg" height="20"
                                        viewBox="0 0 19 20"
                                        fill="@if ($item->evaluation >= 4) #F6A427 @else none @endif"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.12747 4.50667C8.18331 2.61334 8.7108 1.66667 9.49997 1.66667C10.2891 1.66667 10.8166 2.61334 11.8725 4.50667L12.1458 4.99667C12.4458 5.53501 12.5958 5.80417 12.8291 5.98167C13.0625 6.15917 13.3541 6.22501 13.9375 6.35667L14.4675 6.47667C16.5175 6.94084 17.5416 7.17251 17.7858 7.95667C18.0291 8.74001 17.3308 9.55751 15.9333 11.1917L15.5716 11.6142C15.175 12.0783 14.9758 12.3108 14.8866 12.5975C14.7975 12.885 14.8275 13.195 14.8875 13.8142L14.9425 14.3783C15.1533 16.5592 15.2591 17.6492 14.6208 18.1333C13.9825 18.6183 13.0225 18.1758 11.1041 17.2925L10.6066 17.0642C10.0616 16.8125 9.78914 16.6875 9.49997 16.6875C9.2108 16.6875 8.9383 16.8125 8.3933 17.0642L7.89664 17.2925C5.97747 18.1758 5.01747 18.6175 4.37997 18.1342C3.74081 17.6492 3.84664 16.5592 4.05747 14.3783L4.11247 13.815C4.17247 13.195 4.20247 12.885 4.11247 12.5983C4.02414 12.3108 3.82497 12.0783 3.42831 11.615L3.06664 11.1917C1.66914 9.55834 0.970805 8.74084 1.21414 7.95667C1.45831 7.17251 2.48331 6.94001 4.53331 6.47667L5.06331 6.35667C5.64581 6.22501 5.93664 6.15917 6.17081 5.98167C6.40414 5.80417 6.55414 5.53501 6.85414 4.99667L7.12747 4.50667Z"
                                            stroke="#F7A427" stroke-width="1.5" />
                                    </svg>

                                    <svg width="19" class="mr-2 cursor-pointer minim-svg" height="20"
                                        viewBox="0 0 19 20"
                                        fill="@if ($item->evaluation >= 5) #F6A427 @else none @endif"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.12747 4.50667C8.18331 2.61334 8.7108 1.66667 9.49997 1.66667C10.2891 1.66667 10.8166 2.61334 11.8725 4.50667L12.1458 4.99667C12.4458 5.53501 12.5958 5.80417 12.8291 5.98167C13.0625 6.15917 13.3541 6.22501 13.9375 6.35667L14.4675 6.47667C16.5175 6.94084 17.5416 7.17251 17.7858 7.95667C18.0291 8.74001 17.3308 9.55751 15.9333 11.1917L15.5716 11.6142C15.175 12.0783 14.9758 12.3108 14.8866 12.5975C14.7975 12.885 14.8275 13.195 14.8875 13.8142L14.9425 14.3783C15.1533 16.5592 15.2591 17.6492 14.6208 18.1333C13.9825 18.6183 13.0225 18.1758 11.1041 17.2925L10.6066 17.0642C10.0616 16.8125 9.78914 16.6875 9.49997 16.6875C9.2108 16.6875 8.9383 16.8125 8.3933 17.0642L7.89664 17.2925C5.97747 18.1758 5.01747 18.6175 4.37997 18.1342C3.74081 17.6492 3.84664 16.5592 4.05747 14.3783L4.11247 13.815C4.17247 13.195 4.20247 12.885 4.11247 12.5983C4.02414 12.3108 3.82497 12.0783 3.42831 11.615L3.06664 11.1917C1.66914 9.55834 0.970805 8.74084 1.21414 7.95667C1.45831 7.17251 2.48331 6.94001 4.53331 6.47667L5.06331 6.35667C5.64581 6.22501 5.93664 6.15917 6.17081 5.98167C6.40414 5.80417 6.55414 5.53501 6.85414 4.99667L7.12747 4.50667Z"
                                            stroke="#F7A427" stroke-width="1.5" />
                                    </svg>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    <!-- Add the remaining rows as needed -->
                </tbody>
            </table>
        </div>


        </div>
    </main>
    <!-- end: Main -->

    <script>
        var defaultImageUrl = "{{ asset('images/svg/group_10.svg') }}";
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        // Fonction pour charger les sous-catégories via AJAX
        function loadSousCategories(categorie) {
            $.ajax({
                url: '/get-sous-categories', // L'URL de ta route Laravel
                type: 'POST',
                data: {
                    categorie: categorie,
                    _token: '{{ csrf_token() }}' // N'oublie pas d'ajouter le token CSRF pour la sécurité
                },
                success: function(response) {
                    const sousCategorieSelect = document.getElementById('sous-categorie');

                    // Vider les options actuelles
                    sousCategorieSelect.innerHTML = '<option value="none">Sous-Catégories</option>';

                    // Ajouter les nouvelles sous-catégories retournées par le serveur
                    response.forEach(function(sousCategorie) {
                        const option = document.createElement('option');
                        option.value = sousCategorie.id; // Utiliser l'ID comme valeur
                        option.textContent = sousCategorie.name; // Utiliser le nom pour l'affichage
                        sousCategorieSelect.appendChild(option);
                    });
                },
                error: function(xhr) {
                    console.error("Erreur lors du chargement des sous-catégories.");
                }
            });
        }
    </script>

    <script src="{{ asset('js/stars.js') }}"></script>
    <script src="{{ asset('js/imagefile.js') }}"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="js/chart.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>
