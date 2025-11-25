<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MusiquesPredefinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ce seeder est désactivé car l'admin doit uploader ses propres fichiers audio
        // Les APIs gratuites d'audio nécessitent une authentification complexe
        // L'admin peut uploader des fichiers audio (MP3, WAV, OGG) via l'interface /musique

        $this->command->info('Veuillez uploader vos fichiers audio via l\'interface admin (/musique)');
    }
}
