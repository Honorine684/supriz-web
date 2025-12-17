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
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('message')->nullable();
            $table->unsignedTinyInteger('note')->default(5); // 1-5 étoiles
            $table->boolean('visible')->default(false); // Admin doit valider
            $table->string('initiales', 5)->nullable(); // Pour l'avatar (ex: AM)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
