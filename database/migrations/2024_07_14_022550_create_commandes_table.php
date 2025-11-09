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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade'); // Ajout d'un index
            $table->decimal('prix_total_commande', 10, 2)->default(0.00); // Utilisation de decimal pour plus de précision
            $table->decimal('prix_total_livraison', 10, 2)->default(0.00);
            $table->decimal('commission', 10, 2)->default(0.00);
            $table->string('numero_beneficier')->nullable();
            $table->string('selectedOption')->nullable(); // Ajout de l'unicité pour le code de commande
            $table->enum('status', ['encours', 'annulé', 'confirmer', 'livrée'])->default('encours');
            $table->string('code_commandes')->unique(); // Ajout de l'unicité pour le code de commande
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
