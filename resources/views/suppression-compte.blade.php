<!DOCTYPE html>
<html lang="fr">
<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression de compte - Supriz</title>

    {{-- SEO Meta Tags --}}
    @include('partials.seo', [
        'title' => 'Suppression de compte - Supriz',
        'description' => 'Apprenez comment demander la suppression de votre compte Supriz et quelles donnees seront supprimees ou conservees.',
        'keywords' => 'suppression compte, supprimer compte, supprimer donnees, RGPD, Supriz, vie privee'
    ])
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

        h3 {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-top: 25px;
            margin-bottom: 15px;
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

        ol li {
            margin-bottom: 15px;
            font-size: 16px;
        }

        .step-box {
            background: #F0F7FB;
            border-left: 4px solid #005792;
            padding: 20px 25px;
            margin: 20px 0;
            border-radius: 0 10px 10px 0;
        }

        .step-box .step-number {
            display: inline-block;
            width: 30px;
            height: 30px;
            background: #005792;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            font-weight: bold;
            margin-right: 10px;
        }

        .data-box {
            background: #FFF9E6;
            border: 2px solid #FFD700;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
        }

        .data-box.deleted {
            background: #E8F5E9;
            border-color: #4CAF50;
        }

        .data-box.retained {
            background: #FFF3E0;
            border-color: #FF9800;
        }

        .data-box h3 {
            margin-top: 0;
            display: flex;
            align-items: center;
            gap: 10px;
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
            margin-top: 0;
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

        .highlight {
            background: #E3F2FD;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 500;
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

            h3 {
                font-size: 18px !important;
            }

            p, ul li, ol li {
                font-size: 15px !important;
            }

            .content-section {
                padding: 40px 15px !important;
            }

            .step-box {
                padding: 15px 20px;
            }

            .data-box {
                padding: 20px;
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

            p, ul li, ol li {
                font-size: 14px !important;
            }

            .header p {
                font-size: 16px !important;
            }
        }
    </style>
</head>
<body>
    @include('partials.gtm-body')

    <!-- Header -->
    <div class="header">
        <div class="container">
            <a href="{{ route('welcome') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i>
                Retour a l'accueil
            </a>
            <h1 class="mt-4">Suppression de compte</h1>
            <p style="font-size: 18px; margin: 0;">Application Supriz - Guide de suppression</p>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section container">
        <p style="font-size: 18px; color: #666;">
            Chez Supriz, nous respectons votre droit de supprimer votre compte et vos donnees personnelles a tout moment. Cette page vous explique comment proceder et quelles donnees seront affectees.
        </p>

        <h2><i class="bi bi-list-check"></i> Comment demander la suppression de votre compte</h2>
        <p>
            Pour demander la suppression de votre compte Supriz, veuillez suivre les etapes ci-dessous :
        </p>

        <div class="step-box">
            <p style="margin-bottom: 0;"><span class="step-number">1</span> <strong>Envoyez un email</strong> a <a href="mailto:contact@suprizs.com">contact@suprizs.com</a> avec l'objet <span class="highlight">"Demande de suppression de compte"</span></p>
        </div>

        <div class="step-box">
            <p style="margin-bottom: 0;"><span class="step-number">2</span> <strong>Incluez dans votre email :</strong></p>
            <ul style="margin-top: 10px; margin-bottom: 0;">
                <li>L'adresse email associee a votre compte Supriz</li>
                <li>Votre nom d'utilisateur Supriz</li>
            </ul>
        </div>

        <div class="step-box">
            <p style="margin-bottom: 0;"><span class="step-number">3</span> Vous recevrez une <strong>confirmation de reception</strong> sous <span class="highlight">48 heures</span></p>
        </div>

        <div class="step-box">
            <p style="margin-bottom: 0;"><span class="step-number">4</span> La suppression sera effectuee dans un delai de <strong>30 jours</strong> suivant la confirmation de votre demande</p>
        </div>

        <h2><i class="bi bi-trash3"></i> Donnees supprimees</h2>
        <p>
            Lors de la suppression de votre compte, les donnees suivantes seront <strong>definitivement supprimees</strong> de nos serveurs :
        </p>

        <div class="data-box deleted">
            <h3><i class="bi bi-check-circle-fill" style="color: #4CAF50;"></i> Donnees supprimees definitivement</h3>
            <ul style="margin-bottom: 0;">
                <li><strong>Nom d'utilisateur</strong> - Votre identifiant sur l'application</li>
                <li><strong>Adresse email</strong> - L'email associe a votre compte</li>
                <li><strong>Numero de telephone</strong> - Si vous l'avez fourni</li>
                <li><strong>Photo de profil</strong> - Votre image de profil</li>
                <li><strong>Mot de passe</strong> - Donnees d'authentification (chiffrees)</li>
                <li><strong>Historique des messages</strong> - Tous les messages Supriz que vous avez crees</li>
                <li><strong>Preferences</strong> - Vos parametres et preferences de l'application</li>
            </ul>
        </div>

        <h2><i class="bi bi-archive"></i> Donnees conservees</h2>
        <p>
            Pour des raisons legales, comptables et de securite, certaines donnees peuvent etre conservees de maniere limitee :
        </p>

        <div class="data-box retained">
            <h3><i class="bi bi-clock-history" style="color: #FF9800;"></i> Donnees conservees temporairement</h3>
            <ul style="margin-bottom: 0;">
                <li><strong>Logs de transactions</strong> - Conserves pendant <span class="highlight">12 mois</span> pour des raisons comptables et legales</li>
                <li><strong>Donnees anonymisees</strong> - Des statistiques d'utilisation anonymisees peuvent etre conservees a des fins d'amelioration du service</li>
            </ul>
        </div>

        <h2><i class="bi bi-calendar-check"></i> Delais de suppression</h2>
        <ul>
            <li>La suppression complete de vos donnees personnelles est effectuee dans un delai maximum de <strong>30 jours</strong> apres confirmation de votre demande</li>
            <li>Les sauvegardes automatiques contenant vos donnees sont purgees dans un delai de <strong>90 jours</strong></li>
            <li>Les logs de transactions anonymises sont conserves <strong>12 mois</strong> puis automatiquement supprimes</li>
        </ul>

        <h2><i class="bi bi-info-circle"></i> Informations importantes</h2>
        <ul>
            <li>La suppression de votre compte est <strong>irreversible</strong>. Une fois supprime, vous ne pourrez pas recuperer vos donnees.</li>
            <li>Si vous avez des messages Supriz actifs, ils seront egalement supprimes et ne seront plus accessibles par les destinataires.</li>
            <li>Vous pouvez creer un nouveau compte a tout moment apres la suppression.</li>
        </ul>

        <!-- Contact Box -->
        <div class="contact-box">
            <h3><i class="bi bi-envelope-fill"></i> Contact</h3>
            <p>Pour toute question concernant la suppression de votre compte ou vos donnees personnelles, contactez-nous :</p>
            <p style="font-size: 20px; margin: 15px 0;">
                <i class="bi bi-envelope"></i>
                <a href="mailto:contact@suprizs.com">contact@suprizs.com</a>
            </p>
            <p style="font-size: 18px; margin: 10px 0;">
                <i class="bi bi-whatsapp"></i>
                <a href="https://wa.me/22961556680">+229 01 61 55 66 80</a>
            </p>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-5">
            <a href="{{ route('welcome') }}" class="btn btn-lg" style="background: #005792; color: white; padding: 15px 40px; border-radius: 10px; text-decoration: none;">
                Retour a l'accueil
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background: #E8EDF2; padding: 40px 0; margin-top: 60px;">
        <div class="container text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Supriz" style="height: 35px; margin-bottom: 20px;">
            <p style="color: #666; margin: 0;">&copy; {{ date('Y') }} Supriz. Tous droits reserves.</p>
            <p style="color: #999; font-size: 14px; margin-top: 10px;">
                <a href="{{ route('politique.confidentialite') }}" style="color: #666; text-decoration: none;">Politique de confidentialite</a>
                |
                <a href="{{ route('conditions.utilisation') }}" style="color: #666; text-decoration: none;">Conditions d'utilisation</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
