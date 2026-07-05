<!DOCTYPE html>
<html lang="fr">
<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ !empty($paid) ? 'Paiement réussi' : 'Paiement échoué' }} - Supriz</title>

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

    <style>
        @php $ok = !empty($paid); @endphp
        :root {
            --supriz: #005792;
            --ok: #16a34a;
            --ok-soft: #dcfce7;
            --err: #dc2626;
            --err-soft: #fee2e2;
            --ink: #1f2937;
            --muted: #6b7280;
        }
        * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        html, body { margin: 0; height: 100%; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Inter', sans-serif;
            background: linear-gradient(160deg, #eef4f9 0%, #f8fafc 55%, #ffffff 100%);
            color: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            min-height: 100vh;
        }
        .card {
            width: 100%;
            max-width: 380px;
            background: #fff;
            border: 1px solid #eef0f3;
            border-radius: 24px;
            box-shadow: 0 20px 50px -20px rgba(0, 87, 146, .28);
            padding: 40px 28px 32px;
            text-align: center;
            animation: pop .45s cubic-bezier(.18, .89, .32, 1.28) both;
        }
        @keyframes pop { from { opacity: 0; transform: translateY(14px) scale(.96); } to { opacity: 1; transform: none; } }

        .brand {
            font-weight: 800;
            letter-spacing: .5px;
            font-size: 15px;
            color: var(--supriz);
            margin-bottom: 22px;
        }
        .brand span { color: #e11d48; }

        .icon {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            margin: 0 auto 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: {{ $ok ? 'var(--ok-soft)' : 'var(--err-soft)' }};
            animation: haloIn .5s ease .05s both;
        }
        @keyframes haloIn { from { transform: scale(.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }

        .icon svg { width: 52px; height: 52px; display: block; }
        .icon .stroke {
            fill: none;
            stroke: {{ $ok ? 'var(--ok)' : 'var(--err)' }};
            stroke-width: 5;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: draw .6s ease .35s forwards;
        }
        .icon .stroke.d2 { animation-delay: .55s; }
        @keyframes draw { to { stroke-dashoffset: 0; } }

        h1 {
            font-size: 22px;
            font-weight: 800;
            margin: 0 0 10px;
            color: {{ $ok ? 'var(--supriz)' : 'var(--err)' }};
        }
        p.msg {
            font-size: 15px;
            line-height: 1.5;
            color: var(--muted);
            margin: 0 0 26px;
        }
        .hint {
            font-size: 13px;
            color: #9aa3af;
            border-top: 1px solid #f1f3f5;
            padding-top: 18px;
        }
        .hint b { color: var(--supriz); font-weight: 700; }
    </style>
</head>
<body>
    @include('partials.gtm-body')

    <div class="card">
        <div class="brand">SUPRIZ<span>.</span></div>

        @if($ok)
            <div class="icon">
                <svg viewBox="0 0 52 52" aria-hidden="true">
                    <path class="stroke" d="M14 27 l8 8 l16 -18" />
                </svg>
            </div>
            <h1>Paiement réussi&nbsp;!</h1>
            <p class="msg">Merci pour votre confiance. Votre commande est confirmée et sera traitée dans les plus brefs délais. 🎁</p>
        @else
            <div class="icon">
                <svg viewBox="0 0 52 52" aria-hidden="true">
                    <path class="stroke" d="M17 17 L35 35" />
                    <path class="stroke d2" d="M35 17 L17 35" />
                </svg>
            </div>
            <h1>Paiement non abouti</h1>
            <p class="msg">Votre paiement n'a pas pu être confirmé. Aucun montant n'a été prélevé. Vous pouvez réessayer depuis l'application.</p>
        @endif

        <div class="hint">
            Vous pouvez revenir à l'application <b>Supriz</b>.
        </div>
    </div>
</body>
</html>
