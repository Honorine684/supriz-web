<!DOCTYPE html>
<html lang="fr">
<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $message->titre }} - Supriz</title>

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $message->titre }} - Supriz">
    <meta property="og:description" content="{{ Str::limit($message->contenu, 150) }}">
    <meta property="og:image" content="{{ $message->images && count($message->images) > 0 ? $message->images[0] : ($message->media_path ? asset('storage/' . $message->media_path) : asset('images/landing/og-im.png')) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Supriz">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $message->titre }} - Supriz">
    <meta name="twitter:description" content="{{ Str::limit($message->contenu, 150) }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Background from app */
        .app-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -3;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Fallback background (original design) */
        .fallback-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #00789C 0%, #00A3CE 100%);
            background-image: url('{{ asset('images/landing/Bg.svg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -4;
        }

        /* Dark overlay for better readability */
        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: -2;
        }

        /* Floating particles effect */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: floatUp 15s infinite;
        }

        @keyframes floatUp {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.8; }
            90% { opacity: 0.8; }
            100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
        }

        /* Sparkles/stars effect */
        .sparkles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        .sparkle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: white;
            border-radius: 50%;
            animation: sparkle 2s infinite;
        }

        @keyframes sparkle {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.5); }
        }

        /* Confetti */
        .confetti-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1000;
            overflow: hidden;
        }

        .confetti {
            position: absolute;
            top: -10px;
            animation: confettiFall 3s ease-out forwards;
        }

        @keyframes confettiFall {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }

        /* Header */
        .header-logo {
            position: fixed;
            top: 25px;
            left: 25px;
            z-index: 100;
        }

        .header-logo img {
            height: 38px;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.3));
        }

        .header-cta {
            position: fixed;
            top: 25px;
            right: 25px;
            z-index: 100;
        }

        .cta-button {
            background: #E5C9A1;
            color: #005792;
            padding: 12px 22px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        /* Main wrapper */
        .message-wrapper {
            position: relative;
            z-index: 5;
            max-width: 550px;
            width: 100%;
            padding-top: 60px;
        }

        /* Music badge */
        .music-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.95);
            padding: 12px 20px;
            border-radius: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            margin-bottom: 20px;
        }

        .music-badge i {
            font-size: 18px;
            color: #005792;
        }

        .music-badge-info {
            text-align: left;
        }

        .music-badge-title {
            font-weight: 600;
            font-size: 13px;
            color: #1a1a1a;
        }

        .music-badge-category {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Gift Container */
        #giftContainer {
            text-align: center;
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .gift-box {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 255, 255, 0.25);
            border-radius: 25px;
            padding: 45px 50px;
            cursor: pointer;
            transition: all 0.4s ease;
            max-width: 420px;
            margin: 0 auto;
        }

        .gift-box:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .gift-title {
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
            text-shadow: 0 2px 15px rgba(0,0,0,0.4);
        }

        .gift-animation img {
            max-width: 200px;
            height: auto;
            filter: drop-shadow(0 10px 25px rgba(0,0,0,0.3));
        }

        .gift-animation img.animate {
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .gift-hint {
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            margin-top: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .gift-hint i {
            animation: pointUp 1s ease infinite;
        }

        @keyframes pointUp {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        /* Message Content */
        #messageContent {
            display: none;
            animation: revealCard 0.6s ease;
        }

        @keyframes revealCard {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .message-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0,0,0,0.25);
        }

        .card-header {
            padding: 22px 25px;
            border-bottom: 1px solid #f0f0f0;
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        /* Images */
        .image-section {
            background: #f8f9fa;
            display: flex;
            justify-content: center;
        }

        .single-image {
            width: 100%;
            height: auto;
            max-height: 600px;
            object-fit: contain;
            display: block;
        }

        /* Carousel */
        .carousel-wrapper {
            position: relative;
        }

        .carousel-track {
            display: flex;
            transition: transform 0.4s ease;
        }

        .carousel-slide {
            flex: 0 0 100%;
        }

        .carousel-slide img {
            width: 100%;
            height: auto;
            max-height: 600px;
            object-fit: contain;
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: none;
            background: white;
            box-shadow: 0 3px 12px rgba(0,0,0,0.15);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .carousel-btn:hover {
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-btn i {
            font-size: 20px;
            color: #005792;
        }

        .carousel-btn.prev { left: 12px; }
        .carousel-btn.next { right: 12px; }

        .carousel-bars {
            position: absolute;
            top: 8px;
            left: 8px;
            right: 8px;
            display: flex;
            gap: 4px;
            z-index: 15;
        }

        .carousel-bar {
            flex: 1;
            height: 3px;
            border-radius: 2px;
            background: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .carousel-bar.active {
            background: rgba(255, 255, 255, 0.95);
        }

        .carousel-bar.viewed {
            background: rgba(255, 255, 255, 0.95);
        }

        /* Message body */
        .message-body {
            padding: 25px;
        }

        .message-text {
            font-size: 15px;
            line-height: 1.75;
            color: #333;
            white-space: pre-wrap;
        }

        /* Reactions */
        .reactions-section {
            padding: 22px 25px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
        }

        .reactions-title {
            text-align: center;
            color: #666;
            font-size: 13px;
            margin-bottom: 18px;
            font-weight: 500;
        }

        .existing-reactions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-bottom: 18px;
        }

        .reaction-badge {
            background: linear-gradient(135deg, #005792 0%, #0088cc 100%);
            padding: 8px 16px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .reaction-badge span {
            font-size: 18px;
        }

        .reaction-badge strong {
            color: white;
            font-weight: 700;
            font-size: 13px;
        }

        .emoji-buttons-wrapper {
            position: relative;
        }

        .emoji-buttons-wrapper::before,
        .emoji-buttons-wrapper::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 35px;
            pointer-events: none;
            z-index: 2;
        }

        .emoji-buttons-wrapper::before {
            left: 0;
            background: linear-gradient(to right, #f8f9fa 0%, transparent 100%);
        }

        .emoji-buttons-wrapper::after {
            right: 0;
            background: linear-gradient(to left, #f8f9fa 0%, transparent 100%);
        }

        .emoji-buttons {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            overflow-y: hidden;
            padding: 8px 20px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .emoji-buttons::-webkit-scrollbar {
            display: none;
        }

        .emoji-btn {
            width: 62px;
            height: 62px;
            min-width: 62px;
            border-radius: 50%;
            border: 2px solid #e5e5e5;
            background: white;
            font-size: 32px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .emoji-btn:hover {
            transform: scale(1.15) translateY(-3px);
            border-color: #005792;
            box-shadow: 0 5px 20px rgba(0,87,146,0.2);
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            background: #005792;
            color: white;
            padding: 14px 28px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 8px 30px rgba(0,87,146,0.4);
            z-index: 1000;
            animation: toastIn 0.3s ease, toastOut 0.3s ease 1.7s forwards;
        }

        @keyframes toastIn {
            from { opacity: 0; transform: translateX(-50%) translateY(20px); }
            to { opacity: 1; transform: translateX(-50%) translateY(0); }
        }

        @keyframes toastOut {
            to { opacity: 0; }
        }

        /* Mute button */
        .mute-btn {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            box-shadow: 0 4px 20px rgba(255,165,0,0.5);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 100;
            animation: glow 2s ease-in-out infinite;
        }

        .mute-btn:hover {
            transform: scale(1.1);
        }

        .mute-btn i {
            font-size: 22px;
            color: #333;
        }

        @keyframes glow {
            0%, 100% { box-shadow: 0 4px 20px rgba(255,165,0,0.5); }
            50% { box-shadow: 0 4px 35px rgba(255,165,0,0.8), 0 0 50px rgba(255,215,0,0.4); }
        }

        /* Responsive - Tablet */
        @media (max-width: 768px) {
            html, body {
                overflow-x: hidden;
                max-width: 100vw;
            }

            body {
                padding: 15px;
                padding-top: 75px;
                min-height: 100vh;
                min-height: -webkit-fill-available;
            }

            .header-logo { top: 12px; left: 12px; }
            .header-logo img { height: 30px; }
            .header-cta { top: 12px; right: 12px; }
            .cta-button {
                padding: 8px 14px;
                font-size: 11px;
                border-radius: 20px;
            }

            .message-wrapper {
                padding-top: 10px;
                max-width: 100%;
                padding-left: 10px;
                padding-right: 10px;
            }

            .music-badge {
                padding: 10px 16px;
                gap: 8px;
            }
            .music-badge-title { font-size: 12px; }
            .music-badge-category { font-size: 10px; }

            .gift-box {
                padding: 30px 25px;
                border-radius: 20px;
                max-width: 100%;
            }
            .gift-title { font-size: 20px; margin-bottom: 20px; }
            .gift-animation img { max-width: 140px; }
            .gift-hint { font-size: 13px; margin-top: 20px; }

            .message-card {
                border-radius: 16px;
                max-width: 100%;
            }
            .card-header { padding: 18px 20px; }
            .card-title { font-size: 17px; }

            .single-image { height: auto; max-height: none; object-fit: contain; }
            .carousel-slide img { height: auto; max-height: none; object-fit: contain; }
            .carousel-btn { width: 36px; height: 36px; }
            .carousel-btn.prev { left: 8px; }
            .carousel-btn.next { right: 8px; }
            .carousel-btn i { font-size: 18px; }
            .carousel-bars { top: 6px; left: 6px; right: 6px; gap: 3px; }
            .carousel-bar { height: 2px; }

            .message-body { padding: 18px 20px; }
            .message-text { font-size: 14px; line-height: 1.7; }

            .reactions-section { padding: 18px 20px; }
            .reactions-title { font-size: 12px; margin-bottom: 15px; }
            .existing-reactions { gap: 8px; margin-bottom: 15px; }
            .reaction-badge { padding: 6px 12px; }
            .reaction-badge span { font-size: 16px; }
            .reaction-badge strong { font-size: 12px; }

            .emoji-buttons { gap: 10px; padding: 8px 15px; }
            .emoji-btn { width: 54px; height: 54px; min-width: 54px; font-size: 28px; }
            .emoji-buttons-wrapper::before,
            .emoji-buttons-wrapper::after { width: 25px; }

            .mute-btn {
                bottom: 15px;
                right: 15px;
                width: 48px;
                height: 48px;
            }
            .mute-btn i { font-size: 20px; }

            .toast {
                padding: 12px 20px;
                font-size: 13px;
                bottom: 80px;
                max-width: 90%;
            }
        }

        /* Responsive - Small Mobile */
        @media (max-width: 480px) {
            body {
                padding: 10px;
                padding-top: 65px;
            }

            .header-logo { top: 10px; left: 10px; }
            .header-logo img { height: 26px; }
            .header-cta { top: 10px; right: 10px; }
            .cta-button {
                padding: 7px 12px;
                font-size: 10px;
            }

            .message-wrapper {
                padding-left: 5px;
                padding-right: 5px;
            }

            .music-badge {
                padding: 8px 14px;
                margin-bottom: 15px;
            }
            .music-badge i { font-size: 16px; }
            .music-badge-title { font-size: 11px; }
            .music-badge-category { font-size: 9px; }

            .gift-box {
                padding: 25px 20px;
                border-radius: 18px;
            }
            .gift-title { font-size: 18px; margin-bottom: 18px; }
            .gift-animation img { max-width: 120px; }
            .gift-hint { font-size: 12px; margin-top: 18px; }

            .message-card { border-radius: 14px; }
            .card-header { padding: 15px 18px; }
            .card-title { font-size: 16px; }

            .single-image { height: auto; max-height: none; object-fit: contain; }
            .carousel-slide img { height: auto; max-height: none; object-fit: contain; }
            .carousel-btn { width: 32px; height: 32px; }
            .carousel-btn i { font-size: 16px; }
            .carousel-bars { top: 5px; left: 5px; right: 5px; }

            .message-body { padding: 15px 18px; }
            .message-text { font-size: 13px; }

            .reactions-section { padding: 15px 18px; }
            .emoji-buttons { gap: 8px; padding: 8px 12px; }
            .emoji-btn { width: 50px; height: 50px; min-width: 50px; font-size: 26px; }
            .emoji-buttons-wrapper::before,
            .emoji-buttons-wrapper::after { width: 20px; }

            .mute-btn {
                bottom: 12px;
                right: 12px;
                width: 44px;
                height: 44px;
            }
            .mute-btn i { font-size: 18px; }
        }

        /* Very small screens */
        @media (max-width: 360px) {
            .header-logo img { height: 24px; }
            .cta-button { padding: 6px 10px; font-size: 9px; }
            .gift-title { font-size: 16px; }
            .gift-animation img { max-width: 100px; }
            .card-title { font-size: 15px; }
            .emoji-btn { width: 46px; height: 46px; min-width: 46px; font-size: 24px; }
            .emoji-buttons-wrapper::before,
            .emoji-buttons-wrapper::after { width: 15px; }
        }

        /* Fix for iOS Safari */
        @supports (-webkit-touch-callout: none) {
            body {
                min-height: -webkit-fill-available;
            }
        }
    </style>
</head>
<body>
    @include('partials.gtm-body')

    <!-- Background from app or fallback -->
    <div class="fallback-bg"></div>
    @if($message->background_path)
        <div class="app-background" style="background-image: url('{{ asset($message->background_path) }}');"></div>
    @endif
    <div class="bg-overlay"></div>

    <!-- Sparkles effect -->
    <div class="sparkles" id="sparkles"></div>

    <!-- Particles effect -->
    <div class="particles" id="particles"></div>

    <!-- Confetti container -->
    <div class="confetti-container" id="confettiContainer"></div>

    <!-- Header -->
    <div class="header-logo">
        <img src="{{ asset('images/logo.png') }}" alt="SUPRIZ">
    </div>

    <div class="header-cta">
        <a href="/" class="cta-button">Crée le tien !</a>
    </div>

    <!-- Main content -->
    <div class="message-wrapper">
        <!-- Gift view -->
        <div id="giftContainer">
            @if($message->musique)
            <div style="text-align: center; margin-bottom: 20px;">
                <div class="music-badge">
                    <i class="ri-music-2-fill"></i>
                    <div class="music-badge-info">
                        <div class="music-badge-title">{{ $message->musique->titre }}</div>
                        <div class="music-badge-category">{{ $message->sousCategorie->name_sous_categories ?? '' }}</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="gift-box" onclick="openGift()">
                <h2 class="gift-title">{{ $message->titre }}</h2>
                <div class="gift-animation">
                    @php
                        // Le cadeau fait foi sur le champ admin dédié « Image cadeau (Gift) »
                        // = gift_image (fallback image), quelle que soit l'origine (app OU web).
                        // Ainsi site et app affichent le même GIF défini dans le dashboard.
                        $giftImage = $message->sousCategorie->gift_image
                            ?? $message->sousCategorie->image
                            ?? null;
                        $giftImageUrl = $giftImage ? asset('storage/' . $giftImage) : asset('images/clientIamges/Wrapped_Gift.gif');
                        $isGif = $giftImage ? strtolower(pathinfo($giftImage, PATHINFO_EXTENSION)) === 'gif' : true;
                    @endphp
                    <img src="{{ $giftImageUrl }}" alt="Gift" class="{{ $isGif ? '' : 'animate' }}">
                </div>
                <div class="gift-hint">
                    <span>Clique pour ouvrir</span>
                    <i class="ri-cursor-line"></i>
                </div>
            </div>
        </div>

        <!-- Message content -->
        <div id="messageContent">
            @if($message->musique)
            <div style="text-align: center; margin-bottom: 20px;">
                <div class="music-badge">
                    <i class="ri-music-2-fill"></i>
                    <div class="music-badge-info">
                        <div class="music-badge-title">{{ $message->musique->titre }}</div>
                        <div class="music-badge-category">{{ $message->sousCategorie->name_sous_categories ?? '' }}</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="message-card">
                <div class="card-header">
                    <h1 class="card-title">{{ $message->titre }}</h1>
                </div>

                <!-- Images -->
                @if($message->images && count($message->images) > 0)
                    <div class="image-section">
                        @if(count($message->images) == 1)
                            <img src="{{ $message->images[0] }}" alt="{{ $message->titre }}" class="single-image">
                        @else
                            <div class="carousel-wrapper">
                                <!-- Barres style Stories en haut -->
                                <div class="carousel-bars">
                                    @foreach($message->images as $index => $imageUrl)
                                        <span class="carousel-bar {{ $index == 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></span>
                                    @endforeach
                                </div>
                                <div style="overflow: hidden;">
                                    <div class="carousel-track" id="carouselTrack">
                                        @foreach($message->images as $index => $imageUrl)
                                            <div class="carousel-slide">
                                                <img src="{{ $imageUrl }}" alt="{{ $message->titre }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <button class="carousel-btn prev" onclick="prevSlide()">
                                    <i class="ri-arrow-left-s-line"></i>
                                </button>
                                <button class="carousel-btn next" onclick="nextSlide()">
                                    <i class="ri-arrow-right-s-line"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                @elseif($message->media_path)
                    <div class="image-section">
                        @if($message->media_type === 'image')
                            <img src="{{ asset('storage/' . $message->media_path) }}" alt="{{ $message->titre }}" class="single-image">
                        @elseif($message->media_type === 'video')
                            <video controls style="width: 100%; max-height: 400px;">
                                <source src="{{ asset('storage/' . $message->media_path) }}" type="video/mp4">
                            </video>
                        @endif
                    </div>
                @endif

                <!-- Message text -->
                @if($message->contenu)
                <div class="message-body">
                    <p class="message-text">{{ trim($message->contenu) }}</p>
                </div>
                @endif

                <!-- Reactions -->
                @if($message->user_id)
                <div class="reactions-section">
                    <div class="existing-reactions" id="existingReactions">
                        @php
                            $reactions = $message->reactions()
                                ->selectRaw('emoji, count(*) as count')
                                ->groupBy('emoji')
                                ->get();
                        @endphp
                        @foreach($reactions as $reaction)
                            <div class="reaction-badge">
                                <span>{{ $reaction->emoji }}</span>
                                <strong>{{ $reaction->count }}</strong>
                            </div>
                        @endforeach
                    </div>
                    <p class="reactions-title">Exprime ton ressenti !</p>
                    <div class="emoji-buttons-wrapper">
                        <div class="emoji-buttons">
                            @php $emojis = ['🥰', '😎', '😢', '😍', '🤩', '😇', '❤️', '💕', '💗', '💙', '🥳', '💐', '🎉']; @endphp
                            @foreach($emojis as $emoji)
                                <button class="emoji-btn" onclick="addReaction('{{ $emoji }}')">{{ $emoji }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Audio -->
    @if($message->musique)
        <audio id="backgroundMusic" loop style="display: none;">
            <source src="{{ asset('storage/' . $message->musique->fichier_audio) }}" type="audio/mpeg">
        </audio>
        <button class="mute-btn" id="muteButton" onclick="toggleMute()">
            <i id="muteIcon" class="ri-volume-mute-fill"></i>
        </button>
    @endif

    <script>
        // Create sparkles
        const sparklesContainer = document.getElementById('sparkles');
        for (let i = 0; i < 40; i++) {
            const sparkle = document.createElement('div');
            sparkle.className = 'sparkle';
            sparkle.style.left = Math.random() * 100 + '%';
            sparkle.style.top = Math.random() * 100 + '%';
            sparkle.style.animationDelay = Math.random() * 2 + 's';
            sparkle.style.animationDuration = (Math.random() * 2 + 1) + 's';
            sparklesContainer.appendChild(sparkle);
        }

        // Create particles
        const particlesContainer = document.getElementById('particles');
        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 15 + 's';
            particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
            particle.style.width = (Math.random() * 8 + 4) + 'px';
            particle.style.height = particle.style.width;
            particlesContainer.appendChild(particle);
        }

        // Confetti explosion
        function createConfetti() {
            const container = document.getElementById('confettiContainer');
            const colors = ['#FFD700', '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD', '#98D8C8', '#FF9FF3', '#54A0FF'];

            for (let i = 0; i < 80; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.width = (Math.random() * 12 + 6) + 'px';
                    confetti.style.height = confetti.style.width;
                    confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';
                    confetti.style.animationDelay = Math.random() * 0.3 + 's';
                    container.appendChild(confetti);

                    setTimeout(() => confetti.remove(), 3500);
                }, i * 15);
            }
        }

        // Open gift
        function openGift() {
            createConfetti();
            document.getElementById('giftContainer').style.display = 'none';
            document.getElementById('messageContent').style.display = 'block';

            @if($message->musique)
            const audio = document.getElementById('backgroundMusic');
            audio.play().then(() => {
                document.getElementById('muteIcon').className = 'ri-volume-up-fill';
                isMuted = false;
            }).catch(() => {});
            @endif
        }

        // Auto open after 10s
        setTimeout(openGift, 10000);

        // Carousel
        let currentSlide = 0;
        const totalSlides = {{ $message->images ? count($message->images) : 0 }};
        let autoScrollInterval = null;

        function updateCarousel() {
            const track = document.getElementById('carouselTrack');
            if (track) {
                track.style.transform = `translateX(-${currentSlide * 100}%)`;
                document.querySelectorAll('.carousel-bar').forEach((bar, i) => {
                    bar.classList.remove('active', 'viewed');
                    if (i < currentSlide) {
                        bar.classList.add('viewed');
                    } else if (i === currentSlide) {
                        bar.classList.add('active');
                    }
                });
            }
        }

        function nextSlide() {
            if (totalSlides > 1) {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateCarousel();
                resetAutoScroll();
            }
        }

        function prevSlide() {
            if (totalSlides > 1) {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                updateCarousel();
                resetAutoScroll();
            }
        }

        function goToSlide(index) {
            currentSlide = index;
            updateCarousel();
            resetAutoScroll();
        }

        function startAutoScroll() {
            if (totalSlides > 1) {
                autoScrollInterval = setInterval(() => {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    updateCarousel();
                }, 3000);
            }
        }

        function resetAutoScroll() {
            clearInterval(autoScrollInterval);
            setTimeout(startAutoScroll, 2000);
        }

        if (totalSlides > 1) startAutoScroll();

        @if($message->user_id)
        // Reactions
        function addReaction(emoji) {
            let deviceId = localStorage.getItem('supriz_device_id');
            if (!deviceId) {
                deviceId = 'web-' + Math.random().toString(36).substring(2, 15);
                localStorage.setItem('supriz_device_id', deviceId);
            }

            fetch('/api/public/messages/{{ $message->slug }}/react', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Device-Id': deviceId },
                body: JSON.stringify({ emoji: emoji })
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                    updateReactionsDisplay(data.reactions);
                    showToast(data.action === 'added' ? '✓ Réaction ajoutée !' : '✓ Réaction retirée');
                }
            });
        }

        function updateReactionsDisplay(reactions) {
            const container = document.getElementById('existingReactions');
            container.innerHTML = '';
            for (const [emoji, count] of Object.entries(reactions)) {
                container.innerHTML += `<div class="reaction-badge"><span>${emoji}</span><strong>${count}</strong></div>`;
            }
        }

        function showToast(msg) {
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.textContent = msg;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        }
        @endif

        @if($message->musique)
        // Audio
        const audio = document.getElementById('backgroundMusic');
        let isMuted = true;

        function toggleMute() {
            isMuted = !isMuted;
            audio.muted = isMuted;

            if (isMuted) {
                audio.pause();
                document.getElementById('muteIcon').className = 'ri-volume-mute-fill';
                document.getElementById('muteButton').style.background = '#e5e7eb';
                document.getElementById('muteButton').style.animation = 'none';
            } else {
                audio.play();
                document.getElementById('muteIcon').className = 'ri-volume-up-fill';
                document.getElementById('muteButton').style.background = 'linear-gradient(135deg, #FFD700 0%, #FFA500 100%)';
                document.getElementById('muteButton').style.animation = 'glow 2s ease-in-out infinite';
            }
        }
        @endif
    </script>
</body>
</html>
