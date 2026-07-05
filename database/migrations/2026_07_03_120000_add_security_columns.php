<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rôle administrateur : sépare l'accès admin (web) des comptes de l'app mobile.
            $table->boolean('is_admin')->default(false)->after('provider');
            // Verrou lié à la vérification OTP pour la réinitialisation du mot de passe.
            $table->timestamp('password_reset_verified_at')->nullable()->after('otp_expires_at');
        });

        Schema::table('commandes', function (Blueprint $table) {
            // Identifiant de la transaction FedaPay, pour vérifier le paiement côté serveur.
            $table->unsignedBigInteger('fedapay_transaction_id')->nullable()->after('code_commandes');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_admin', 'password_reset_verified_at']);
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('fedapay_transaction_id');
        });
    }
};
