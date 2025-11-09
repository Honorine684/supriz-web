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
        Schema::create('supriz', function (Blueprint $table) {
            $table->id();
            $table->string('nom_categorie_suprise');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->string('numero_destinateur');
            $table->string('prix');
            $table->string('Description_supriz');
            $table->enum('status', ['encours','livrée'])->default('encours'); // c'est encours lorsque  personne n'a pas encore été livrée
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supriz');
    }
};
