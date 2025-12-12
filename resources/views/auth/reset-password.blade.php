<!doctype html>
<html lang="fr">
<head>
    @include('partials.gtm-head')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe - Supriz</title>
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
        <!-- Reset password form to the right -->
        <div class="w-full md:w-1/2 p-8">
            <h2 class="text-2xl font-bold text-gray-900">Nouveau mot de passe</h2>
            <p class="text-color-gray mb-3">Veuillez entrer votre nouveau mot de passe</p>
            <form method="POST" action="{{ route('password.reset') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- Errors -->
                @if($errors->any())
                <div class="bg-red-500 text-center p-2 rounded text-white">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                    <input id="password" name="password" placeholder="Entrez votre nouveau mot de passe" type="password" required
                        class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm"
                        autocomplete="new-password" minlength="8">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                    <input id="password_confirmation" name="password_confirmation" placeholder="Confirmez votre mot de passe" type="password" required
                        class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm"
                        autocomplete="new-password" minlength="8">
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full btn-design text-white font-semibold py-2 px-4 rounded-md shadow-sm hover:btn-design focus:outline-none focus:ring-2 focus:ring-offset-2">
                        Réinitialiser le mot de passe
                    </button>
                </div>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600">
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Retour à la connexion</a>
            </p>
        </div>
    </div>
</body>
</html>
