<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->date('date_livraison')->nullable()->after('numero_beneficier');
            $table->time('heure_livraison')->nullable()->after('date_livraison');
            $table->decimal('latitude_livraison', 10, 7)->nullable()->after('heure_livraison');
            $table->decimal('longitude_livraison', 10, 7)->nullable()->after('latitude_livraison');
            $table->text('adresse_livraison')->nullable()->after('longitude_livraison');
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn([
                'date_livraison',
                'heure_livraison',
                'latitude_livraison',
                'longitude_livraison',
                'adresse_livraison',
            ]);
        });
    }
};
