<!DOCTYPE html>
<html lang="fr">
<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message inactif - Supriz</title>

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Message inactif - Supriz">
    <meta property="og:description" content="Ce message supriz est actuellement inactif.">
    <meta property="og:image" content="{{ asset('images/landing/og-im.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Supriz">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Message inactif - Supriz">
    <meta name="twitter:description" content="Ce message supriz est actuellement inactif.">
    <meta name="twitter:image" content="{{ asset('images/landing/og-im.png') }}">

    <link rel="shortcut icon" href="{{asset('/images/svg/llogosupriz2.svg')}}" type="image/x-icon">
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
            background-image: url('{{ asset('images/landing/Bg.svg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .error-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            background: #FEF3C7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-icon i {
            font-size: 60px;
            color: #D97706;
        }

        h1 {
            font-size: 28px;
            color: #1a1a1a;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .message-title {
            font-size: 18px;
            color: #005792;
            margin-bottom: 20px;
            font-weight: 600;
        }

        p {
            font-size: 16px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .btn-home {
            background: #005792;
            color: white;
            padding: 14px 32px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-home:hover {
            background: #003555;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 87, 146, 0.3);
        }

        @media (max-width: 480px) {
            .error-container {
                padding: 40px 25px;
            }

            h1 {
                font-size: 24px;
            }

            .message-title {
                font-size: 16px;
            }

            p {
                font-size: 14px;
            }

            .error-icon {
                width: 100px;
                height: 100px;
            }

            .error-icon i {
                font-size: 50px;
            }
        }
    </style>
</head>
<body>
    @include('partials.gtm-body')

    <div class="error-container">
        <div class="error-icon">
            <i class="ri-error-warning-line"></i>
        </div>

        <h1>Message inactif</h1>

        @if($message->titre)
            <div class="message-title">"{{ $message->titre }}"</div>
        @endif

        <p>
            Ce message supriz n'est actuellement pas disponible.<br>
            Il a peut-être été désactivé par son créateur.
        </p>

        <a href="{{ route('home') }}" class="btn-home">
            <i class="ri-home-line" style="margin-right: 8px;"></i>
            Retour à l'accueil
        </a>
    </div>
</body>
</html>
