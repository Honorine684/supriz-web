<?php

namespace App\Providers;

use App\Models\Commandes;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Compteur de commandes EN ATTENTE de livraison (status 'confirmer' =
        // payées, en attente de traitement) — affiché en badge sur le sidebar.
        // Exclut volontairement les 'encours' (paniers non payés).
        View::composer('components.sidebar', function ($view) {
            $count = 0;
            try {
                $count = Commandes::where('status', 'confirmer')->count();
            } catch (\Throwable $e) {
                // En cas d'erreur DB (ex. migrations non jouées), pas de badge.
            }
            $view->with('commandesEnAttenteCount', $count);
        });
    }
}
