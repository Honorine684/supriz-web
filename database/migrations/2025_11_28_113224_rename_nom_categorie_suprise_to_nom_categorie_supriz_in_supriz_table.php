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
        Schema::table('supriz', function (Blueprint $table) {
            $table->renameColumn('nom_categorie_suprise', 'nom_categorie_supriz');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supriz', function (Blueprint $table) {
            $table->renameColumn('nom_categorie_supriz', 'nom_categorie_suprise');
        });
    }
};
