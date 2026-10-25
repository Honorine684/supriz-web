<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande confirmée - Supriz</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f7f7f7; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f7f7f7;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 480px; background-color: #ffffff; border-radius: 12px; overflow: hidden;">
                    <tr>
                        <td style="background: linear-gradient(135deg, #005792, #2C5F2D); padding: 32px 40px; text-align: center;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: 700; color: #ffffff;">SUPRIZ</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 32px 40px;">
                            <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 600; color: #000000; text-align: center;">
                                ✅ Commande confirmée
                            </h2>
                            <p style="margin: 0 0 24px; font-size: 15px; line-height: 24px; color: #545454; text-align: center;">
                                Bonjour <strong>{{ $userName }}</strong>,<br>
                                votre paiement a bien été reçu. Votre commande est confirmée.
                            </p>

                            <div style="background-color: #f7f7f7; border-radius: 8px; padding: 16px 20px; margin-bottom: 24px;">
                                <p style="margin: 0 0 8px; font-size: 13px; color: #8c8c8c;">Code de commande</p>
                                <p style="margin: 0; font-size: 18px; font-weight: 700; color: #005792; letter-spacing: 1px;">{{ $codeCommande }}</p>
                            </div>

                            <p style="margin: 0 0 8px; font-size: 14px; color: #545454;"><strong>Produits :</strong> {{ $produits }}</p>
                            <p style="margin: 0; font-size: 16px; font-weight: 700; color: #000000;">Total : {{ number_format($total, 0, ',', ' ') }} FCFA</p>

                            <p style="margin: 24px 0 0; font-size: 14px; line-height: 22px; color: #545454; text-align: center;">
                                Nous vous contacterons pour la livraison.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 40px;">
                            <hr style="border: none; border-top: 1px solid #e5e5e5; margin: 0;">
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 24px 40px 32px;">
                            <p style="margin: 0; font-size: 13px; line-height: 20px; color: #8c8c8c; text-align: center;">
                                Merci de votre confiance !
                            </p>
                        </td>
                    </tr>
                </table>

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
