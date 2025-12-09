<!doctype html>
<html lang="en">
<head>
    @include('partials.gtm-head')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    @vite('resources/css/app.css')
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href={{asset('css/main.css') }}>
</head>
<body class="flex items-center justify-center min-h-screen">
    @include('partials.gtm-body')

    <div class="flex bg-white rounded-lg shadow-lg max-w-4xl">
        <!-- Image to the left -->
        <div class="w-1/2 hidden lg:block">
            <img src={{ asset('images/left_image.jpg') }} alt="Company Logo" class="w-3/4 h-auto object-cover rounded-l-lg">
        </div>
        <!-- Login form to the right -->
        <div class="w-full md:w-1/2 p-8">
            <h2 class="text-2xl font-bold  text-gray-900">Connexion</h2>
            <p class="text-color-gray mb-3">Veuillez entrer vos identifiant de connexion</p>
            <form method="POST" action="{{ url('/login') }}"  class="space-y-6">
                @csrf
                <!-- Email -->
                @if($errors->any())
                <div class="bg-red-500 text-center p-2 rounded text-white">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" placeholder="Entrez votre mail" type="email" required
                        class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none  sm:text-sm"
                        autocomplete="email">
                </div>
                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    </div>
                    <input id="password" name="password" placeholder="Entrer votre mot de passe" type="password" required
                        class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none  sm:text-sm"
                        autocomplete="current-password">

                </div>
                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full btn-design text-white font-semibold py-2 px-4 rounded-md shadow-sm hover:btn-design focus:outline-none focus:ring-2 focus:ring-offset-2">
                        Se connecter
                    </button>
                </div>
            </form>

        </div>
    </div>
</body>
</html>
