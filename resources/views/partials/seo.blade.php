{{-- SEO Meta Tags --}}
<meta name="description" content="{{ $description ?? 'Supriz - Créez et partagez des messages surprises personnalisés avec vos proches. Anniversaire, mariage, voeux, une expérience unique et mémorable.' }}">
<meta name="keywords" content="{{ $keywords ?? 'supriz, message surprise, cadeau personnalisé, anniversaire, mariage, voeux, nouvel an, noel, message personnalisé, carte virtuelle, surprise, cadeau original, Bénin, Afrique' }}">
<meta name="author" content="Supriz">
<meta name="robots" content="index, follow">
<meta name="language" content="French">
<meta name="revisit-after" content="7 days">

{{-- Favicon - Multiple formats pour compatibilité --}}
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="icon" type="image/svg+xml" href="{{ asset('images/svg/llogosupriz2.svg') }}">
<link rel="apple-touch-icon" href="{{ asset('images/svg/llogosupriz2.svg') }}">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ url()->current() }}">

{{-- Open Graph Meta Tags --}}
<meta property="og:title" content="{{ $title ?? 'Supriz - Envoie un message supriz inoubliable' }}">
<meta property="og:description" content="{{ $description ?? 'Créez et partagez des messages surprises personnalisés avec vos proches. Une expérience unique et mémorable.' }}">
<meta property="og:image" content="{{ asset('images/landing/og-im.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="Supriz">
<meta property="og:locale" content="fr_FR">

{{-- Twitter Card Meta Tags --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title ?? 'Supriz - Envoie un message supriz inoubliable' }}">
<meta name="twitter:description" content="{{ $description ?? 'Créez et partagez des messages surprises personnalisés avec vos proches. Une expérience unique et mémorable.' }}">
<meta name="twitter:image" content="{{ asset('images/landing/og-im.png') }}">
