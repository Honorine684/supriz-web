<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $message->titre }} - Supriz</title>

    <!-- Open Graph Meta Tags pour le partage sur les réseaux sociaux -->
    <meta property="og:title" content="{{ $message->titre }} - Supriz">
    <meta property="og:description" content="{{ Str::limit($message->message_texte, 150) }}">
    <meta property="og:image" content="{{ $message->image ? asset('storage/' . $message->image) : asset('images/logo.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Supriz">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $message->titre }} - Supriz">
    <meta name="twitter:description" content="{{ Str::limit($message->message_texte, 150) }}">
    <meta name="twitter:image" content="{{ $message->image ? asset('storage/' . $message->image) : asset('images/logo.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #00789C 0%, #00A3CE 100%);
            background-image: url('{{ asset('images/landing/Bg.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }


        /* Logo en haut à gauche */
        .header-logo {
            position: absolute;
            top: 30px;
            left: 30px;
            z-index: 10;
        }

        .header-logo img {
            height: 35px;
        }

        /* Bouton en haut à droite */
        .header-cta {
            position: absolute;
            top: 30px;
            right: 30px;
            z-index: 10;
        }

        .cta-button {
            background: #E5C9A1;
            color: #005792;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s;
        }

        .cta-button:hover {
            transform: translateY(-2px);
        }

        /* Container principal */
        .message-wrapper {
            position: relative;
            z-index: 5;
            max-width: 600px;
            width: 100%;
            padding: 0 20px;
        }

        /* Gift Container */
        #giftContainer {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
        }

        .gift-box {
            background: rgba(255,255,255,0.1);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 20px;
            padding: 50px 80px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            text-align: center;
        }

        .gift-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .gift-animation img {
            max-width: 280px;
            width: 100%;
            height: auto;
        }

        .gift-animation img.animate {
            animation: bounce 1s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        /* Badge catégorie/musique au-dessus de la carte */
        .category-badge {
            background: white;
            color: #005792;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 13px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-card {
            animation: fadeIn 0.5s ease;
        }

        audio {
            height: 35px;
            border-radius: 20px;
        }

        audio::-webkit-media-controls-panel {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
        }

        @media (max-width: 768px) {
            * {
                box-sizing: border-box !important;
            }

            html, body {
                overflow-x: hidden !important;
                max-width: 100vw !important;
                width: 100% !important;
            }

            body {
                padding: 10px;
                padding-top: 80px;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #giftContainer {
                min-height: calc(100vh - 160px);
                padding: 10px 0;
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .gift-box {
                padding: 20px 15px;
                width: 95%;
                max-width: 100%;
                margin: 0 auto;
            }

            .gift-box h3 {
                font-size: 16px !important;
                margin-bottom: 25px !important;
                line-height: 1.5 !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }

            .gift-animation {
                display: flex;
                justify-content: center;
            }

            .gift-animation img {
                max-width: 160px !important;
                width: 100% !important;
                height: auto !important;
            }

            /* Header */
            .header-logo {
                top: 15px;
                left: 15px;
            }

            .header-logo img {
                height: 28px;
            }

            .header-cta {
                top: 15px;
                right: 15px;
            }

            .cta-button {
                padding: 8px 12px;
                font-size: 11px;
                border-radius: 16px;
                white-space: nowrap;
            }

            /* Message wrapper */
            .message-wrapper {
                padding: 0 10px;
                max-width: 100%;
                width: 100%;
            }

            /* Category badge */
            .category-badge {
                padding: 6px 10px !important;
                font-size: 10px !important;
                max-width: 95% !important;
                margin: 0 auto 12px !important;
                display: inline-flex !important;
                align-items: center !important;
                gap: 6px !important;
                flex-wrap: nowrap !important;
            }

            .category-badge i {
                font-size: 13px !important;
                flex-shrink: 0 !important;
            }

            .category-badge > div {
                flex-shrink: 1 !important;
                min-width: 0 !important;
            }

            .category-badge div {
                font-size: 10px !important;
                white-space: nowrap !important;
            }

            .category-badge div:last-child {
                font-size: 8px !important;
            }

            /* Message card */
            .message-card {
                border-radius: 16px !important;
                margin: 0 !important;
            }

            /* Title */
            .message-card > div:first-child {
                padding: 18px 16px 14px 16px !important;
            }

            .message-card h1 {
                font-size: 16px !important;
                line-height: 1.4 !important;
            }

            /* Media */
            .message-card img {
                max-height: 400px !important;
                height: auto !important;
                object-fit: contain !important;
            }

            .message-card video {
                max-height: 250px !important;
                height: auto !important;
                object-fit: contain !important;
            }

            /* Content */
            .message-card > div:last-child {
                padding: 16px 16px 20px 16px !important;
            }

            .message-card p {
                font-size: 13px !important;
                line-height: 1.6 !important;
            }

            /* Mute button */
            #muteButton {
                bottom: 20px !important;
                right: 20px !important;
                width: 50px !important;
                height: 50px !important;
            }

            #muteIcon {
                font-size: 22px !important;
            }

            audio {
                max-width: 150px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
                padding-top: 70px;
            }

            .header-logo {
                top: 12px;
                left: 12px;
            }

            .header-logo img {
                height: 26px;
            }

            .header-cta {
                top: 12px;
                right: 12px;
            }

            .cta-button {
                padding: 7px 12px;
                font-size: 11px;
            }

            .message-wrapper {
                padding: 0 5px;
            }

            .category-badge {
                padding: 6px 14px;
                font-size: 11px;
            }

            .message-card > div:first-child {
                padding: 16px 15px 12px 15px !important;
            }

            .message-card h1 {
                font-size: 15px !important;
            }

            .message-card img {
                max-height: 350px !important;
                height: auto !important;
                object-fit: contain !important;
            }

            .message-card video {
                max-height: 220px !important;
                height: auto !important;
                object-fit: contain !important;
            }

            .message-card > div:last-child {
                padding: 15px 15px 20px 15px !important;
            }

            .message-card p {
                font-size: 12px !important;
            }

            #muteButton {
                bottom: 15px !important;
                right: 15px !important;
                width: 48px !important;
                height: 48px !important;
            }

            #muteIcon {
                font-size: 20px !important;
            }
        }
    </style>
</head>
<body>
    <!-- Logo en haut à gauche -->
    <div class="header-logo">
        <img src="{{ asset('images/logo.png') }}" alt="SUPRIZ">
    </div>

    <!-- Bouton CTA en haut à droite -->
    <div class="header-cta">
        <a href="/" class="cta-button">
            Surprends-en un aussi !
        </a>
    </div>

    <!-- Message principal -->
    <div class="message-wrapper">
        <!-- Gift Animation (shown first) -->
        <div id="giftContainer">
            @if($message->musique)
            <div style="text-align: center; margin-bottom: 20px;">
                <div class="category-badge" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="ri-music-2-line" style="font-size: 16px;"></i>
                    <div style="text-align: left;">
                        <div style="font-weight: 600; font-size: 13px;">{{ $message->musique->titre }}</div>
                        <div style="font-size: 11px; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">{{ $message->sousCategorie->name_sous_categories }}</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="gift-box" onclick="showMessage()">
                <h3 style="color: white; text-align: center; margin-bottom: 40px; font-size: 22px; font-weight: 600;">{{ $message->titre }}</h3>
                <div class="gift-animation">
                    @php
                        $giftImage = $message->sousCategorie->image ?? null;
                        $giftImageUrl = $giftImage ? asset('storage/' . $giftImage) : asset('images/clientIamges/Wrapped_Gift.gif');
                        $isGif = $giftImage ? strtolower(pathinfo($giftImage, PATHINFO_EXTENSION)) === 'gif' : true;
                        $animateClass = $isGif ? '' : 'animate';
                    @endphp
                    <img src="{{ $giftImageUrl }}" alt="Gift" class="{{ $animateClass }}">
                </div>
            </div>
        </div>

        <!-- Message Content (hidden initially) -->
        <div id="messageContent" style="display: none;">
            <!-- Badge musique/catégorie en haut -->
            @if($message->musique)
            <div style="text-align: center; margin-bottom: 20px;">
                <div class="category-badge" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="ri-music-2-line" style="font-size: 16px;"></i>
                    <div style="text-align: left;">
                        <div style="font-weight: 600; font-size: 13px;">{{ $message->musique->titre }}</div>
                        <div style="font-size: 11px; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px;">{{ $message->sousCategorie->name_sous_categories }}</div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Carte du message - design exact de l'image -->
            <div class="message-card" style="background: white; border-radius: 20px; overflow: hidden; max-width: 600px; margin: 0 auto; box-shadow: 0 8px 32px rgba(0,0,0,0.2);">
            <!-- Titre -->
            <div style="padding: 25px 30px 20px 30px;">
                <h1 style="font-size: 18px; font-weight: 600; color: #1a1a1a; margin: 0;">{{ $message->titre }}</h1>
            </div>

            <!-- Image ou vidéo pleine largeur -->
            @if($message->media_path)
                <div style="width: 100%; display: flex; justify-content: center; background: #f8f9fa;">
                    @if($message->media_type === 'image')
                        <img src="{{ asset('storage/' . $message->media_path) }}" alt="{{ $message->titre }}" style="width: 100%; height: auto; max-height: 600px; object-fit: contain; display: block;">
                    @elseif($message->media_type === 'video')
                        <video controls style="width: 100%; max-height: 400px; object-fit: contain; display: block;">
                            <source src="{{ asset('storage/' . $message->media_path) }}" type="video/mp4">
                            Votre navigateur ne supporte pas la vidéo.
                        </video>
                    @endif
                </div>
            @endif

            <!-- Contenu du message -->
            @if($message->contenu)
                <div style="padding: 20px 30px 30px 30px;">
                    <p style="font-size: 14px; line-height: 1.7; color: #1a1a1a; margin: 0; white-space: pre-wrap;">{{ trim($message->contenu) }}</p>
                </div>
            @endif
        </div>
        </div>

        <!-- Audio caché qui joue automatiquement -->
        @if($message->musique)
            <audio id="backgroundMusic" autoplay style="display: none;">
                <source src="{{ asset('storage/' . $message->musique->fichier_audio) }}" type="audio/mpeg">
            </audio>
        @endif
    </div>

    <script>
        // Function to show message content
        function showMessage() {
            document.getElementById('giftContainer').style.display = 'none';
            document.getElementById('messageContent').style.display = 'block';
        }

        // Auto-show message after 10 seconds
        setTimeout(() => {
            showMessage();
        }, 10000);
    </script>

    <!-- Bouton Mute en bas à droite -->
    @if($message->musique)
    <button id="muteButton" onclick="toggleMute()" style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; border-radius: 50%; background: white; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.2); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; z-index: 100;">
        <i id="muteIcon" class="ri-volume-up-fill" style="font-size: 24px; color: #005792;"></i>
    </button>

    <script>
        const audio = document.getElementById('backgroundMusic');
        const muteButton = document.getElementById('muteButton');
        const muteIcon = document.getElementById('muteIcon');
        let isMuted = false;

        function toggleMute() {
            isMuted = !isMuted;
            audio.muted = isMuted;

            if (isMuted) {
                muteIcon.className = 'ri-volume-mute-fill';
                muteButton.style.background = '#e5e7eb';
                audio.pause();
            } else {
                muteIcon.className = 'ri-volume-up-fill';
                muteButton.style.background = 'white';
                audio.play();
            }
        }

        // Limiter la durée de lecture à 30 secondes
        audio.addEventListener('timeupdate', function() {
            if (audio.currentTime >= 30) {
                audio.pause();
                audio.currentTime = 0;
            }
        });

        // Attendre que la page soit complètement chargée
        window.addEventListener('load', function() {
            // Essayer de jouer la musique
            const playPromise = audio.play();

            if (playPromise !== undefined) {
                playPromise.then(function() {
                    // La lecture a commencé avec succès
                    console.log('Musique lancée automatiquement');
                }).catch(function(error) {
                    // L'autoplay a été bloqué par le navigateur
                    console.log('Autoplay bloqué, cliquez sur le bouton pour activer la musique');
                    // Afficher une indication visuelle que la musique est muette
                    isMuted = true;
                    audio.muted = true;
                    muteIcon.className = 'ri-volume-mute-fill';
                    muteButton.style.background = '#e5e7eb';

                    // Ajouter une animation pour attirer l'attention
                    muteButton.style.animation = 'pulse 2s infinite';
                });
            }
        });

        // Ajouter l'animation CSS pour le bouton
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.1); }
            }
        `;
        document.head.appendChild(style);

        // Autoriser la lecture au premier clic sur la page
        document.addEventListener('click', function() {
            if (audio.paused && !isMuted) {
                audio.play();
            }
        }, { once: true });
    </script>
    @endif
</body>
</html>
