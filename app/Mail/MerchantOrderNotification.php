<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MerchantOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public string $codeCommande;
    public string $produits;
    public int $total;
    public string $clientNom;
    public string $clientNumero;

    public function __construct(
        string $codeCommande,
        string $produits,
        int $total,
        string $clientNom,
        string $clientNumero
    ) {
        $this->codeCommande = $codeCommande;
        $this->produits = $produits;
        $this->total = $total;
        $this->clientNom = $clientNom;
        $this->clientNumero = $clientNumero;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle commande #' . $this->codeCommande . ' - Supriz',
            from: 'noreply@suprizs.com',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.merchant-order',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
