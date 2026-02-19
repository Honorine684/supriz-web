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
    <title>Supriz - Paramètres </title>
</head>


<body class="text-gray-800 bg-dark-design custmer-toggle font-inter">
    @include('partials.gtm-body')

    <!-- start: Sidebar -->
    @include('components.sidebar')
    <!-- end: Sidebar -->

    <!-- start: Main -->
    <main
        class="custmer-toggle w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main rounded-20 ">


        <a href=""><span class="mr-4 cursor-pointer border-bottom-design text-color-design">Information
                Générale</span></a>
        <hr class="mb-6">

        <h2 class="font-bold">Profil</h2>

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


        <form action="{{ route('admin.updateprofil') }}" class=" mt-6 relative" method="POST" enctype="multipart/form-data">
            @csrf <!-- Protection CSRF -->

            <div class="h-input-file rounded-pofil relative">
                @if ($user->profil_image)
                    <img src="{{ asset('storage/' . $user->profil_image) }}" id="imagePreview"
                        class="mt-3 cursor-pointer rounded-pofil" alt="Image de profil">
                @else
                    <img src="{{ asset('images/profil/no-profile.png') }}" id="imagePreview"
                        class="mt-3 cursor-pointer rounded-pofil" alt="Image par défaut">
                @endif

                <input type="file" id="imageInput" accept="image/*" name="profil_image"
                    class="relative opacity-0 cursor-pointer h-input-file rounded-full -t-15">
            </div>
            <div class="index-30">
                <div class="flex index-30 items-center justify-between">
                    <div class="w-full grid mr-7">
                        <span class="font-bold mb-3">Nom : </span>
                        <input type="text" value="{{ $user->nom }}"
                            class="border-none mr-10 index-30 rounded bg-transparent" name="nom"
                            placeholder="Admin">
                    </div>

                    <div class="w-full grid mr-7">
                        <span class="font-bold mb-3">Prenom : </span>
                        <input type="text" value="{{ $user->prenom }}"
                            class="border-none mr-10 rounded bg-transparent" name="prenom" placeholder="Admin">
                    </div>

                    <div class="w-full ml-7 grid">
                        <span class="font-bold mb-3">Email : </span>
                        <input type="email" class="bg-transparent rounded border-none" value="{{ $user->email }}"
                            name="email" placeholder="johndoe@gmail.com">
                    </div>
                </div>

                <input type="submit" name="submit"
                    class="w-80 relative right-0 rounded p-2 text-white cursor-pointer btn-design mt-10"
                    value="Enregistrer les modifications">
            </div>
        </form>

        <!-- Section Mot de passe -->
        <div class="mt-10 border-t pt-6">
            <h2 class="font-bold mb-4">Modifier le mot de passe</h2>
            <p class="text-gray-600 mb-4">Pour changer votre mot de passe, un email vous sera envoyé avec un lien de réinitialisation.</p>
            <form action="{{ route('admin.send.password.reset') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <button type="submit"
                    class="w-80 rounded p-2 text-white cursor-pointer btn-design flex items-center justify-center gap-2">
                    <i class="ri-mail-send-line"></i>
                    Recevoir un email de réinitialisation
                </button>
            </form>
        </div>












    </main>
    <!-- end: Main -->
    <script>
        var defaultImageUrl = "{{ asset('images/profil/no-profile.png') }}";
    </script>

    <script src="js/imagefile.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="js/chart.js"></script>
    <script src="js/script.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>

</body>

</html>
