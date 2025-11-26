<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions Générales d'Utilisation - Supriz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            color: #333;
            line-height: 1.8;
        }

        .header {
            background: linear-gradient(135deg, #005792, #2C5F2D);
            color: white;
            padding: 60px 0;
        }

        .content-section {
            padding: 60px 0;
            max-width: 900px;
            margin: 0 auto;
        }

        h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 28px;
            font-weight: 600;
            color: #005792;
            margin-top: 40px;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            margin-bottom: 15px;
        }

        ul {
            margin-bottom: 20px;
        }

        ul li {
            margin-bottom: 10px;
            font-size: 16px;
        }

        .contact-box {
            background: #F8FBFD;
            border: 2px solid #005792;
            border-radius: 10px;
            padding: 30px;
            margin-top: 40px;
            text-align: center;
        }

        .contact-box h3 {
            color: #005792;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .contact-box a {
            color: #005792;
            font-weight: 600;
            text-decoration: none;
        }

        .contact-box a:hover {
            text-decoration: underline;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .back-btn:hover {
            color: #FFD700;
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .header {
                padding: 40px 0;
            }

            h1 {
                font-size: 32px !important;
                line-height: 1.2;
            }

            h2 {
                font-size: 22px !important;
                margin-top: 30px;
            }

            p, ul li {
                font-size: 15px !important;
            }

            .content-section {
                padding: 40px 15px !important;
            }

            .contact-box {
                padding: 20px;
                margin-top: 30px;
            }

            .contact-box h3 {
                font-size: 20px;
            }

            .contact-box p {
                font-size: 14px !important;
            }

            .contact-box a {
                font-size: 16px !important;
                word-break: break-all;
            }

            .btn-lg {
                font-size: 16px !important;
                padding: 12px 30px !important;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 28px !important;
            }

            h2 {
                font-size: 20px !important;
            }

            p, ul li {
                font-size: 14px !important;
            }

            .header p {
                font-size: 16px !important;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="container">
            <a href="{{ route('welcome') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i>
                Retour à l'accueil
            </a>
            <h1 class="mt-4">Conditions Générales d'Utilisation</h1>
            <p style="font-size: 18px; margin: 0;">Dernière mise à jour : {{ date('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section container">
        <p style="font-size: 18px; color: #666;">
            Bienvenue sur Supriz, un service conçu pour transmettre des émotions simplement, sans inscription, sans collecte intrusive, et avec un lien unique valable 24h. En accédant à notre site ou en utilisant le service Message Supriz, vous acceptez les présentes Conditions Générales d'Utilisation (CGU).
        </p>

        <h2>Objet du service</h2>
        <p>
            Supriz permet à tout utilisateur de créer un message personnalisé (anniversaire, remerciement, mots doux, etc.) et de le partager via un lien temporaire. Ce lien est actif pendant 24 heures, puis le message est automatiquement supprimé.
        </p>

        <h2>Accès et utilisation</h2>
        <p>
            L'accès au service est libre, gratuit et sans création de compte.<br>
            En utilisant Supriz, vous vous engagez à :
        </p>
        <ul>
            <li>Utiliser le service dans un cadre personnel, bienveillant et respectueux</li>
            <li>Ne pas détourner Supriz à des fins commerciales, frauduleuses ou illégales</li>
            <li>Ne pas publier de contenu offensant, haineux, discriminatoire, violent ou contraire à la loi</li>
            <li>Ne pas tenter d'altérer le fonctionnement du site ou d'accéder à des messages qui ne vous sont pas destinés</li>
        </ul>

        <h2>Contenu des messages</h2>
        <p>
            Vous êtes entièrement responsable du contenu que vous rédigez et partagez via Supriz.<br>
            Supriz ne modère pas les messages avant publication.
        </p>

        <h2>Durée de vie des messages</h2>
        <p>
            Chaque message Supriz est accessible via un lien unique pendant 24 heures.<br>
            Passé ce délai, le message est automatiquement supprimé de nos serveurs.<br>
            Aucune copie n'est conservée. Aucun historique n'est stocké.
        </p>

        <h2>Confidentialité et sécurité</h2>
        <p>
            Supriz ne collecte aucune donnée personnelle sensible (pas d'e-mail, pas de numéro, pas de mot de passe).<br>
            Les messages sont stockés temporairement et ne sont accessibles qu'à ceux qui possèdent le lien.<br>
            Pour plus de détails, consultez notre <a href="{{ route('politique.confidentialite') }}" style="color: #005792; font-weight: 600; text-decoration: underline;">Politique de confidentialité</a>.
        </p>

        <h2>Disponibilité du service</h2>
        <p>
            Nous faisons tout notre possible pour assurer un service fluide et sécurisé.<br>
            Cependant, Supriz peut être temporairement indisponible pour :
        </p>
        <ul>
            <li>Maintenance technique</li>
            <li>Mise à jour du service</li>
            <li>Raisons indépendantes de notre volonté (panne, surcharge, etc.)</li>
        </ul>
        <p>Aucune garantie n'est donnée quant à la disponibilité continue du service.</p>

        <h2>Propriété intellectuelle</h2>
        <p>
            Le nom Supriz, son logo, son design, ses textes et ses fonctionnalités sont la propriété exclusive de Supriz.<br>
            Toute reproduction, modification ou exploitation non autorisée est interdite.
        </p>

        <h2>Modification des CGU</h2>
        <p>
            Supriz peut modifier les présentes conditions à tout moment pour s'adapter à l'évolution du service ou aux obligations légales.<br>
            La date de dernière mise à jour est toujours indiquée en haut de cette page.
        </p>

        <!-- Contact Box -->
        <div class="contact-box">
            <h3>Contact</h3>
            <p>Pour toute question, suggestion ou signalement, vous pouvez nous écrire à :</p>
            <p style="font-size: 20px; margin: 0;">
                <i class="bi bi-envelope-fill"></i>
                <a href="mailto:contact@suprizs.com">contact@suprizs.com</a>
            </p>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-5">
            <a href="{{ route('welcome') }}" class="btn btn-lg" style="background: #005792; color: white; padding: 15px 40px; border-radius: 10px; text-decoration: none;">
                Retour à l'accueil
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background: #E8EDF2; padding: 40px 0; margin-top: 60px;">
        <div class="container text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Supriz" style="height: 35px; margin-bottom: 20px;">
            <p style="color: #666; margin: 0;">© {{ date('Y') }} Supriz. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
