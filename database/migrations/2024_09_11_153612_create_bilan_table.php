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
        Schema::create('bilan', function (Blueprint $table) {
            $table->id();
            $table->integer('nbr_produit_vendu');
            $table->double('somme_total');
            $table->double('benefices');
            $table->integer('nbr_livraison');
            $table->integer('montant_total_supriz');
            $table->double('prix_total_livraison');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bilan');
    }
};
