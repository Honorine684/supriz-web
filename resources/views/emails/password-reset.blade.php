<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe - Supriz</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #005792, #2C5F2D); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700;">SUPRIZ</h1>
                            <p style="margin: 10px 0 0; color: #E5C9A1; font-size: 14px;">Réinitialisation du mot de passe</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; font-size: 16px; color: #333; line-height: 1.6;">
                                Bonjour <strong>{{ $userName }}</strong>,
                            </p>

                            <p style="margin: 0 0 20px; font-size: 16px; color: #333; line-height: 1.6;">
                                Une demande de réinitialisation de mot de passe a été effectuée pour votre compte administrateur.
                            </p>

                            <p style="margin: 0 0 30px; font-size: 16px; color: #333; line-height: 1.6;">
                                Cliquez sur le bouton ci-dessous pour définir un nouveau mot de passe :
                            </p>

                            <!-- Button -->
                            <div style="text-align: center; margin-bottom: 30px;">
                                <a href="{{ $resetUrl }}" style="display: inline-block; background: linear-gradient(135deg, #005792, #2C5F2D); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 8px; font-size: 16px; font-weight: 600;">
                                    Réinitialiser mon mot de passe
                                </a>
                            </div>

                            <!-- Warning -->
                            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px 20px; margin-bottom: 30px; border-radius: 5px;">
                                <p style="margin: 0; font-size: 14px; color: #856404; line-height: 1.5;">
                                    <strong>Attention :</strong> Ce lien expire dans <strong>60 minutes</strong>. Si vous n'avez pas demandé cette réinitialisation, ignorez simplement cet email.
                                </p>
                            </div>

                            <!-- Alternative link -->
                            <p style="margin: 0 0 10px; font-size: 14px; color: #666; line-height: 1.6;">
                                Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :
                            </p>
                            <p style="margin: 0 0 30px; font-size: 12px; color: #005792; word-break: break-all; background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                                {{ $resetUrl }}
                            </p>

                            <p style="margin: 0 0 10px; font-size: 16px; color: #333; line-height: 1.6;">
                                Cordialement,
                            </p>
                            <p style="margin: 0; font-size: 16px; color: #005792; font-weight: 600;">
                                L'équipe Supriz
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 1px solid #e0e0e0;">
                            <p style="margin: 0 0 10px; font-size: 14px; color: #666;">
                                <a href="mailto:contact@suprizs.com" style="color: #005792; text-decoration: none;">contact@suprizs.com</a>
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #999;">
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
