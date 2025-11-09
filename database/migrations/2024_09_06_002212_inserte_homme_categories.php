<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Import the DB facade

return new class extends Migration
{
    public function up(): void
    {
        // Vérifiez que la table existe avant d'insérer des données
        if (!Schema::hasTable('homme_categories')) {
            Schema::create('homme_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name_id_categories')->unique();
                $table->string('libelle_homme_categories');
                $table->timestamps();
            });
        }

        // Insert default categories into homme_categories table
        DB::beginTransaction();
        try {
            DB::table('homme_categories')->insert([
                ['name_id_categories' => 'h', 'libelle_homme_categories' => 'Homme', 'created_at' => now(), 'updated_at' => now()],
                ['name_id_categories' => 'f', 'libelle_homme_categories' => 'Femme', 'created_at' => now(), 'updated_at' => now()],
                ['name_id_categories' => 'e', 'libelle_homme_categories' => 'Enfant', 'created_at' => now(), 'updated_at' => now()],
                ['name_id_categories' => 's', 'libelle_homme_categories' => 'Supriz', 'created_at' => now(), 'updated_at' => now()],
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('homme_categories');
    }
};
