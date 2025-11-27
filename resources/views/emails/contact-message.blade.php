<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #005792, #2C5F2D); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700;">📬 Nouveau message de contact</h1>
                            <p style="margin: 10px 0 0; color: #E5C9A1; font-size: 14px;">Via le formulaire de contact du site</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <!-- Informations de l'expéditeur -->
                            <div style="background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
                                <h2 style="margin: 0 0 20px; font-size: 18px; color: #005792; border-bottom: 2px solid #E5C9A1; padding-bottom: 10px;">
                                    Informations de l'expéditeur
                                </h2>
                                <table width="100%" cellpadding="8" cellspacing="0">
                                    <tr>
                                        <td style="font-weight: 600; color: #666; font-size: 14px; width: 30%;">Nom :</td>
                                        <td style="color: #333; font-size: 14px;">{{ $data['name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color: #666; font-size: 14px;">Email :</td>
                                        <td style="color: #333; font-size: 14px;">
                                            <a href="mailto:{{ $data['email'] }}" style="color: #005792; text-decoration: none;">{{ $data['email'] }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color: #666; font-size: 14px;">Sujet :</td>
                                        <td style="color: #333; font-size: 14px; font-weight: 600;">{{ $data['subject'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 600; color: #666; font-size: 14px;">Date :</td>
                                        <td style="color: #333; font-size: 14px;">{{ date('d/m/Y à H:i') }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Message -->
                            <div style="background-color: #ffffff; border: 2px solid #005792; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                                <h3 style="margin: 0 0 15px; font-size: 16px; color: #005792;">Message :</h3>
                                <p style="margin: 0; font-size: 15px; color: #333; line-height: 1.7; white-space: pre-wrap;">{{ $data['message'] }}</p>
                            </div>

                            <!-- Call to Action -->
                            <div style="text-align: center; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
                                <p style="margin: 0 0 15px; font-size: 14px; color: #666;">Répondre directement à cet email :</p>
                                <a href="mailto:{{ $data['email'] }}?subject=Re: {{ $data['subject'] }}"
                                   style="display: inline-block; background-color: #005792; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px;">
                                    Répondre à {{ $data['name'] }}
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e0e0e0;">
                            <p style="margin: 0; font-size: 12px; color: #999;">
                                Ce message a été envoyé depuis le formulaire de contact de <strong>suprizs.com</strong>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
