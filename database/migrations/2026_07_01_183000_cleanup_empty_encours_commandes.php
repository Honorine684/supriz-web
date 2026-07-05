<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table réelle: 'achat' (singulier) — cf. modèle Achat et migration
        // create_achat_table. Garde défensive si la table n'existe pas encore.
        if (!Schema::hasTable('commandes') || !Schema::hasTable('achat')) {
            return;
        }

        DB::table('commandes')
            ->where('status', 'encours')
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('achat')
                  ->whereColumn('achat.commande_id', 'commandes.id');
            })
            ->delete();
    }

    public function down(): void
    {
        // Suppression définitive: pas de rollback possible.
    }
};
