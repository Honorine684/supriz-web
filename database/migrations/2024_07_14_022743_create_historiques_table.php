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
        Schema::create('historiques', function (Blueprint $table) {
            $table->id();
            $table->string('id_commande'); // soit c'est l'id de la commande supriz ou d'une commande simple
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->text('description');
            $table->enum('type', ['simple', 'supriz'])->default('simple'); // Use a valid value here
            $table->integer('prix')->nullable();
            $table->string('code_historiques')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historiques');
    }
};
