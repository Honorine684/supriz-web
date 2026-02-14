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
        Schema::create('arriere_plans', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('image'); // Chemin de l'image
            $table->foreignId('sous_categorie_id')->nullable()->constrained('homme_sous_categories')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->integer('ordre')->default(0); // Pour l'ordre d'affichage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arriere_plans');
    }
};
