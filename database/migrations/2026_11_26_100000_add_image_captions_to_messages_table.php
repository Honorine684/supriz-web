<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Légende par photo (lot 5, affichage en pile de polaroids).
     * Tableau aligné sur la colonne `images` : image_captions[i] est la
     * légende de images[i], ou null. Les anciens messages restent à null.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->json('image_captions')->nullable()->after('images');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('image_captions');
        });
    }
};
