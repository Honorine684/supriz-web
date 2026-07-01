<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marchands', function (Blueprint $table) {
            // Jours travaillés: ["mon","tue","wed","thu","fri","sat","sun"]
            $table->json('working_days')->nullable()->after('telephone');
            // Plages horaires: [{"open":"09:00","close":"13:00"},{"open":"15:00","close":"19:00"}]
            $table->json('working_hours')->nullable()->after('working_days');
        });
    }

    public function down(): void
    {
        Schema::table('marchands', function (Blueprint $table) {
            $table->dropColumn(['working_days', 'working_hours']);
        });
    }
};
