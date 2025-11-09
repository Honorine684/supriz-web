<?php
// Migration pour les catégories homme
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homme_categories', function (Blueprint $table) {
            $table->id();
            $table->enum('name_id_categories', ['h', 'f','e','s'])->default('h')->unique();
            $table->string('libelle_homme_categories')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homme_categories');
    }
};