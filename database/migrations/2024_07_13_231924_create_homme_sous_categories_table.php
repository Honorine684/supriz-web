<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homme_sous_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_categorie_id') // Renamed to follow convention
                  ->constrained('homme_categories') // References 'homme_categories' table
                  ->onDelete('cascade');
            $table->string('name_sous_categories');
            $table->integer('prix')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homme_sous_categories');
    }
};
