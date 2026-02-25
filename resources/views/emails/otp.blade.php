<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code de vérification Supriz</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f7f7f7; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f7f7f7;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 480px; background-color: #ffffff; border-radius: 12px; overflow: hidden;">
                    <!-- Header avec couleur Supriz -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #005792, #2C5F2D); padding: 32px 40px; text-align: center;">
                            <img src="https://suprizs.com/storage/images/logo.png" alt="Supriz" width="60" height="60" style="display: block; margin: 0 auto 12px;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: 700; color: #ffffff;">SUPRIZ</h1>
                        </td>
                    </tr>

                    <!-- Contenu principal -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 600; color: #000000; text-align: center;">
                                Votre code de vérification
                            </h2>
                            <p style="margin: 0 0 24px; font-size: 15px; line-height: 24px; color: #545454; text-align: center;">
                                Utilisez ce code pour vérifier votre compte
                            </p>

                            <!-- Code OTP -->
                            <div style="background-color: #E4D1B9; border-radius: 12px; padding: 24px; text-align: center; margin-bottom: 24px;">
                                <span style="font-size: 40px; font-weight: 700; letter-spacing: 10px; color: #005792;">{{ $otp }}</span>
                            </div>

                            <p style="margin: 0; font-size: 14px; line-height: 22px; color: #545454; text-align: center;">
                                Ce code expire dans <strong style="color: #005792;">10 minutes</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Séparateur -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <hr style="border: none; border-top: 1px solid #e5e5e5; margin: 0;">
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 40px 32px;">
                            <p style="margin: 0; font-size: 13px; line-height: 20px; color: #8c8c8c; text-align: center;">
                                Si vous n'avez pas demandé ce code, ignorez cet email.
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Copyright -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 480px;">
                    <tr>
                        <td style="padding: 24px 40px; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #8c8c8c;">
                                © {{ date('Y') }} Supriz. Tous droits réservés.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
