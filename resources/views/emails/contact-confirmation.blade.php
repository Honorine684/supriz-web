<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation - Supriz</title>
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
                            <p style="margin: 10px 0 0; color: #E5C9A1; font-size: 14px;">Merci pour votre message</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; font-size: 16px; color: #333; line-height: 1.6;">
                                Bonjour <strong>{{ $data['name'] }}</strong>,
                            </p>

                            <p style="margin: 0 0 20px; font-size: 16px; color: #333; line-height: 1.6;">
                                Nous avons bien reçu votre message concernant : <strong>{{ $data['subject'] }}</strong>
                            </p>

                            <p style="margin: 0 0 30px; font-size: 16px; color: #333; line-height: 1.6;">
                                Notre équipe reviendra vers vous dans les plus brefs délais, généralement sous 24 à 48 heures.
                            </p>

                            <!-- Recap du message -->
                            <div style="background-color: #f8f9fa; border-left: 4px solid #005792; padding: 20px; margin-bottom: 30px; border-radius: 5px;">
                                <p style="margin: 0 0 10px; font-size: 14px; color: #666; font-weight: 600;">Votre message :</p>
                                <p style="margin: 0; font-size: 14px; color: #555; line-height: 1.6; white-space: pre-wrap;">{{ $data['message'] }}</p>
                            </div>

                            <p style="margin: 0 0 10px; font-size: 16px; color: #333; line-height: 1.6;">
                                Merci de votre confiance,
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
