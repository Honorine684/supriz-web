<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public string $userName;
    public string $codeCommande;
    public string $produits;
    public int $total;

    public function __construct(string $userName, string $codeCommande, string $produits, int $total)
    {
        $this->userName = $userName;
        $this->codeCommande = $codeCommande;
        $this->produits = $produits;
        $this->total = $total;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre commande est confirmée - Supriz',
            from: 'noreply@suprizs.com',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
