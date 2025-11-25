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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('contenu')->nullable();
            $table->foreignId('sous_categorie_id')->constrained('homme_sous_categories')->onDelete('cascade');
            $table->string('media_path')->nullable();
            $table->enum('media_type', ['image', 'video'])->nullable();
            $table->foreignId('musique_id')->nullable()->constrained('musiques')->onDelete('set null');
            $table->dateTime('date_expiration');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
