<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Environnement FedaPay
    |--------------------------------------------------------------------------
    | 'sandbox' pour les tests, 'live' pour la production.
    */
    'environment' => env('FEDAPAY_ENV', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Clé secrète (sk_...)
    |--------------------------------------------------------------------------
    | NE JAMAIS coder cette valeur en dur ni la committer. Uniquement via .env.
    */
    'secret_key' => env('FEDAPAY_SECRET_KEY'),

    'public_key' => env('FEDAPAY_PUBLIC_KEY'),

];
