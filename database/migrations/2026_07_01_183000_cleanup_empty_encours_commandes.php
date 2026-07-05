<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('commandes')
            ->where('status', 'encours')
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('achats')
                  ->whereColumn('achats.commande_id', 'commandes.id');
            })
            ->delete();
    }

    public function down(): void
    {
        // Suppression définitive: pas de rollback possible.
    }
};
