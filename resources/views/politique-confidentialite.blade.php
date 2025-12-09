<!DOCTYPE html>
<html lang="fr">
<head>
    @include('partials.gtm-head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politique de confidentialité - Supriz</title>

    {{-- SEO Meta Tags --}}
    @include('partials.seo', [
        'title' => 'Politique de confidentialité - Supriz',
        'description' => 'Consultez notre politique de confidentialité pour comprendre comment Supriz protège vos données personnelles et respecte votre vie privée.',
        'keywords' => 'politique confidentialité, protection données, vie privée, RGPD, Supriz, données personnelles'
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
    @include('partials.gtm-body')

    <!-- Header -->
    <div class="header">
        <div class="container">
            <a href="{{ route('welcome') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i>
                Retour à l'accueil
            </a>
            <h1 class="mt-4">Politique de confidentialité</h1>
            <p style="font-size: 18px; margin: 0;">Dernière mise à jour : {{ date('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Content -->
    <div class="content-section container">
        <p style="font-size: 18px; color: #666;">
            Chez Supriz, nous croyons que les émotions méritent d'être partagées en toute simplicité et en toute confidentialité. Cette politique de confidentialité a pour but de vous expliquer, en toute transparence, comment nous traitons les données liées à votre utilisation de notre service Message Supriz.
        </p>

        <h2>Qui sommes-nous ?</h2>
        <p>
            Supriz est un service en ligne qui permet à chacun d'envoyer des messages personnalisés (vœux, remerciements, mots doux…) via un lien unique et éphémère. Notre mission est de faciliter l'expression des émotions, sans complications, sans inscription, et surtout sans intrusion dans votre vie privée.
        </p>

        <h2>Quelles données collectons-nous ?</h2>
        <p>
            Nous avons fait le choix de la simplicité et du respect de votre vie privée. C'est pourquoi nous ne collectons aucune donnée personnelle sensible.
        </p>
        <p>Concrètement, nous ne collectons pas :</p>
        <ul>
            <li>Aucune adresse e-mail</li>
            <li>Aucun numéro de téléphone</li>
            <li>Aucun mot de passe</li>
            <li>Aucun nom complet obligatoire</li>
        </ul>

        <h2>Comment utilisons-nous ces données ?</h2>
        <p>Les données que vous saisissez sont utilisées uniquement pour :</p>
        <ul>
            <li>Générer un lien unique vers votre message Supriz</li>
            <li>Permettre au destinataire de consulter ce message pendant une durée limitée</li>
            <li>Assurer la stabilité, la sécurité et la performance du service</li>
            <li>Analyser de manière anonyme l'usage global du site (nombre de messages créés, durée moyenne de lecture, etc.)</li>
        </ul>
        <p><strong>Nous ne vendons, ne louons, ni ne partageons vos données avec des tiers. Jamais.</strong></p>

        <h2>Durée de conservation</h2>
        <p>Nous croyons à l'éphémère, comme un mot doux glissé dans une main.</p>
        <ul>
            <li>Chaque lien Supriz est actif pendant 24 heures à compter de sa création.</li>
            <li>Passé ce délai, le message est automatiquement supprimé de nos serveurs.</li>
            <li>Aucune copie n'est conservée. Aucun historique n'est stocké.</li>
            <li>Les données techniques anonymes sont conservées au maximum 30 jours à des fins de diagnostic et de sécurité.</li>
        </ul>

        <h2>Cookies et technologies similaires</h2>
        <p>Nous utilisons uniquement des cookies essentiels pour :</p>
        <ul>
            <li>Assurer le bon fonctionnement du site</li>
            <li>Mémoriser vos préférences de langue</li>
            <li>Analyser de manière anonyme l'usage global du site</li>
        </ul>
        <p>Nous ne faisons aucun suivi publicitaire, aucun reciblage, aucun profilage.</p>

        <h2>Sécurité des données</h2>
        <p>Nous mettons en œuvre des mesures techniques et organisationnelles pour protéger vos données :</p>
        <ul>
            <li>Connexion sécurisée via HTTPS</li>
            <li>Stockage temporaire chiffré des messages</li>
            <li>Accès restreint aux serveurs</li>
            <li>Suppression automatique des messages après 24h</li>
        </ul>

        <h2>Vos droits</h2>
        <p>Même si nous ne collectons pas de données personnelles identifiables, vous avez des droits :</p>
        <ul>
            <li><strong>Droit à l'information :</strong> vous avez le droit de savoir comment vos données sont traitées</li>
            <li><strong>Droit à l'effacement :</strong> vous ne pouvez supprimer manuellement un message avant les 24h</li>
            <li><strong>Droit à la limitation :</strong> vous pouvez choisir de ne pas utiliser le service si vous ne souhaitez pas que des données techniques soient collectées</li>
        </ul>
        <p>Pour toute demande, contactez-nous à : <a href="mailto:contact@suprizs.com">contact@suprizs.com</a></p>

        <h2>Utilisation par les mineurs</h2>
        <p>
            Supriz est un service destiné à un public général. Nous ne collectons pas sciemment de données auprès de mineurs. Si vous êtes parent ou tuteur et pensez qu'un message enfreint cette règle, contactez-nous pour suppression immédiate.
        </p>

        <h2>Hébergement et juridiction</h2>
        <p>
            Nos serveurs sont hébergés dans un environnement sécurisé. En utilisant Supriz, vous acceptez que vos données soient traitées conformément à cette politique, dans le respect des lois applicables au Bénin.
        </p>

        <h2>Modifications de cette politique</h2>
        <p>
            Nous pouvons mettre à jour cette politique à tout moment pour refléter l'évolution de notre service ou des obligations légales. La date de dernière mise à jour est toujours indiquée en haut de cette page.
        </p>

        <!-- Contact Box -->
        <div class="contact-box">
            <h3>Contact</h3>
            <p>Pour toute question, suggestion ou demande liée à la confidentialité, vous pouvez nous écrire à :</p>
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
