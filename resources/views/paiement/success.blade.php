<!DOCTYPE html>
<html>
<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Réussi - Supriz</title>

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Paiement Réussi - Supriz">
    <meta property="og:description" content="Votre paiement a été effectué avec succès. Merci pour votre confiance !">
    <meta property="og:image" content="{{ asset('images/landing/og-im.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Supriz">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Paiement Réussi - Supriz">
    <meta name="twitter:description" content="Votre paiement a été effectué avec succès. Merci pour votre confiance !">
    <meta name="twitter:image" content="{{ asset('images/landing/og-im.png') }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
</head>

<style>
    body{
        display:flex;
        align-items:center;
        justify-content: center;
        height: 100vh;
    }
</style>


<body>
    @include('partials.gtm-body')

    <h1>Paiement réussi... !</h1>
    
</body>
</html>
