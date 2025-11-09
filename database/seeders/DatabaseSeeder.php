<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appeler le seeder pour créer le compte admin
        $this->call([
            PanelSeeder::class,
        ]);
    }
}
