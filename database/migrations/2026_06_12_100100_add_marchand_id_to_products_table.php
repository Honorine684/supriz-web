<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('marchand_id')
                ->nullable()
                ->after('entreprise')
                ->constrained('marchands')
                ->nullOnDelete();
        });

        // Backfill : 1 marchand par valeur unique de `entreprise`
        $entreprises = DB::table('products')
            ->whereNotNull('entreprise')
            ->where('entreprise', '!=', '')
            ->distinct()
            ->pluck('entreprise');

        $now = now();
        foreach ($entreprises as $nom) {
            $nomClean = trim($nom);
            if ($nomClean === '') {
                continue;
            }

            $marchandId = DB::table('marchands')
                ->where('nom', $nomClean)
                ->value('id');

            if ($marchandId === null) {
                $marchandId = DB::table('marchands')->insertGetId([
                    'nom' => $nomClean,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            DB::table('products')
                ->where('entreprise', $nom)
                ->update(['marchand_id' => $marchandId]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('marchand_id');
        });
    }
};
