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
        Schema::create('bilan_entreprise', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('nbr_produits_vendu');
            $table->string('montant_encaisse');
            $table->foreignId('bilanId')->constrained('bilan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bilan_entreprise');
    }
};
