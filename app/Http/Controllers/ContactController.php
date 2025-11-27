<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactConfirmation;
use App\Mail\ContactMessage;

class ContactController extends Controller
{
    /**
     * Envoyer le message de contact
     */
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        try {
            // 1. Envoyer l'email à l'équipe Supriz (contact@suprizs.com)
            Mail::to('contact@suprizs.com')->send(new ContactMessage($data));

            // 2. Envoyer l'email de confirmation à l'utilisateur
            Mail::to($request->email)->send(new ContactConfirmation($data));

            return redirect(route('welcome') . '#contact')->with('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
        } catch (\Exception $e) {
            return redirect(route('welcome') . '#contact')->with('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.');
        }
    }
}
