<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre code OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            color: #0056b3;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            color: #ff6b6b;
        }
        .footer {
            font-size: 14px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bonjour,</h1>
        <p>Nous avons reçu une demande pour sécuriser votre connexion. Veuillez utiliser le code ci-dessous pour compléter votre authentification :</p>
        <p class="otp-code">Votre code OTP : {{ $otp }}</p>
        <p>Ce code est valable pendant 10 minutes. Si vous n'avez pas initié cette demande, veuillez ignorer cet email ou nous contacter immédiatement.</p>
        <p>Merci de votre confiance.</p>
        <p>Cordialement,</p>
        <p><strong>L'équipe de support</strong></p>
        <p class="footer">Cet e-mail a été envoyé automatiquement, veuillez ne pas y répondre.</p>
    </div>
</body>
</html>
