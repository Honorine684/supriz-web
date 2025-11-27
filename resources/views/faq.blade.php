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
    <title>Supriz - Catégorie</title>
</head>


<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">

    <!-- start: Sidebar -->
    @include('components.sidebar')
    <!-- end: Sidebar -->

    <!-- start: Main -->
    <main class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20 ">

        <div class="pt-6 pb-0 pl-6 pr-6 mb-5 overflow-x-auto bg-design lg:col-span-2 rounded-20">
            <div class="flex items-center justify-between mb-5">
                <img src="{{ asset('/images/svg/llogosupriz2.svg') }}" alt="">
                <h4 class="font-bold text-right text-white">FAQ</h4>
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

    <form action="{{ isset($faq) ? route('admin.faq.update', $faq->id) : route('admin.store.faq') }}" method="POST">
        @csrf
        @if(isset($faq))
            @method('PUT')
        @endif
    
        <div class="grid w-full">
            <span class="mb-2">Catégories</span>
            <select name="type_faq" id="type_faq" class="p-1 rounded">
                <option value="none">Catégories</option>
                <option value="supriz" {{ isset($faq) && $faq->type_faq == 'supriz' ? 'selected' : '' }}>Supriz</option>
                <option value="app" {{ isset($faq) && $faq->type_faq == 'app' ? 'selected' : '' }}>App</option>
            </select>
        </div>
    
        <div class="flex w-full mt-2">
            <div class="grid w-1/2">
                <span class="mb-2">Questions</span>
                <textarea name="question_faq" id="question_faq" class="rounded p-3 resize-none" cols="3" rows="5">{{ isset($faq) ? $faq->asq : '' }}</textarea>
            </div>

            <div class="grid w-1/2 ml-10">
                <span class="mb-2">Réponse</span>
                <textarea name="reponse_faq" id="reponse_faq" class="rounded p-3 resize-none" cols="3" rows="5">{{ isset($faq) ? $faq->response : '' }}</textarea>
            </div>
        </div>
    
        <input type="submit" class="w-full p-2 mt-2 text-white rounded cursor-pointer btn-design" value="{{ isset($faq) ? 'Modifier' : 'Enregistrer' }}">
    </form>
    
        
        

        <p class="mt-3 mb-2">Liste des catégories</p>

        <div
        class="p-3 overflow-x-auto bg-white border border-gray-100 shadow-md rounded-20 shadow-black/5 lg:col-span-2">
        <table class="w-full">

            <tbody>

                @foreach ($faqs as $faq)
    <tr>
        <td class="px-4 py-2 border-b w-60 border-b-gray-50">
            <span class="text-[13px] font-medium text-gray-400">
                {{$faq->asq}}
            </span>
        </td>
        <td class="px-4 py-2 border-b w-60 border-b-gray-50">
            <span class="text-[13px] font-medium text-gray-400">{{$faq->response}}</span>
        </td>

        <td class="px-4 py-2 border-b w-60 border-b-gray-50">
            <span class="text-[13px] font-medium text-gray-400">
                {{$faq->created_at->format('d-m-Y')}}
            </span>
        </td>

        <td class="flex px-4 py-2">
            <!-- Bouton Modifier -->
            <a href="{{ route('admin.faq.edit', $faq->id) }}" class="mr-2 text-[13px] font-medium text-gray-400 cursor-pointer">
                <img class="relative top-1" src="{{ asset('/images/svg/edit-3_svgrepo.com.svg') }}" alt="Modifier">
            </a>

            <!-- Bouton Supprimer -->
            <form action="{{ route('admin.faq.destroy', $faq->id) }}" method="POST" class="text-[13px] font-medium text-gray-400 cursor-pointer">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer la FAQ ?')">
                    <img class="relative top-1" src="{{ asset('/images/svg/delete-left_svgrepo.com.svg') }}" alt="Supprimer">
                </button>
            </form>
        </td>
    </tr>
@endforeach


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

</body>

</html>
