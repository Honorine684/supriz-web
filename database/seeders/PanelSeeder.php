<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class PanelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer ou mettre à jour le compte admin
        User::updateOrCreate(
            ['email' => 'admin@supriz.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'Supriz',
                'numero' => '0000000000',
                'password' => '
                ', // Le mutateur du modèle User hashera automatiquement
                'email_verified_at' => now(),
            ]
        );
    }
}
