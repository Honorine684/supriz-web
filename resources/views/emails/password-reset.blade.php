<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe - Supriz</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f7f7f7; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f7f7f7;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 480px; background-color: #ffffff; border-radius: 8px;">
                    <!-- Header avec logo -->
                    <tr>
                        <td style="padding: 32px 40px 24px; text-align: center;">
                            <img src="https://suprizs.com/storage/images/logo.png" alt="Supriz" width="60" height="60" style="display: block; margin: 0 auto;">
                        </td>
                    </tr>

                    <!-- Contenu principal -->
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <h1 style="margin: 0 0 16px; font-size: 24px; font-weight: 600; color: #000000; text-align: center;">
                                Réinitialiser votre mot de passe
                            </h1>
                            <p style="margin: 0 0 8px; font-size: 16px; line-height: 24px; color: #545454; text-align: center;">
                                Bonjour {{ $userName }},
                            </p>
                            <p style="margin: 0 0 24px; font-size: 16px; line-height: 24px; color: #545454; text-align: center;">
                                Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe.
                            </p>

                            <!-- Bouton -->
                            <div style="text-align: center; margin-bottom: 24px;">
                                <a href="{{ $resetUrl }}" style="display: inline-block; background-color: #000000; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 16px; font-weight: 600;">
                                    Réinitialiser le mot de passe
                                </a>
                            </div>

                            <p style="margin: 0; font-size: 14px; line-height: 22px; color: #545454; text-align: center;">
                                Ce lien expire dans <strong>60 minutes</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Séparateur -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <hr style="border: none; border-top: 1px solid #e5e5e5; margin: 0;">
                        </td>
                    </tr>

                    <!-- Lien alternatif -->
                    <tr>
                        <td style="padding: 24px 40px;">
                            <p style="margin: 0 0 8px; font-size: 13px; line-height: 20px; color: #8c8c8c; text-align: center;">
                                Si le bouton ne fonctionne pas, copiez ce lien :
                            </p>
                            <p style="margin: 0; font-size: 12px; line-height: 18px; color: #545454; text-align: center; word-break: break-all;">
                                {{ $resetUrl }}
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
                                Si vous n'avez pas demandé ce changement, ignorez cet email.
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
