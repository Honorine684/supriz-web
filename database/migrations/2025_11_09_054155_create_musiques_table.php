<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('musiques', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('fichier_audio')->nullable(); // Pour les musiques uploadées
            $table->string('api_url')->nullable(); // Pour les musiques depuis l'API
            $table->foreignId('sous_categorie_id')->nullable()->constrained('homme_sous_categories')->onDelete('cascade');
            $table->integer('duree')->nullable(); // Durée en secondes
            $table->boolean('is_active')->default(true);
            $table->boolean('is_predefined')->default(false); // True si c'est une musique prédéfinie
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('musiques');
    }
};
