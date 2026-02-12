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
        Schema::table('messages', function (Blueprint $table) {
            $table->integer('max_views')->nullable()->after('views_count');
            $table->boolean('unique_view')->default(false)->after('max_views');
        });

        // Table pour tracker les vues uniques
        Schema::create('message_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained()->onDelete('cascade');
            $table->string('viewer_ip')->nullable();
            $table->string('device_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            $table->index(['message_id', 'viewer_ip']);
            $table->index(['message_id', 'device_id']);
        });

        // Table pour les réactions aux messages
        Schema::create('message_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained()->onDelete('cascade');
            $table->string('emoji');
            $table->string('viewer_ip')->nullable();
            $table->string('device_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            $table->index(['message_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_reactions');
        Schema::dropIfExists('message_views');

        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['max_views', 'unique_view']);
        });
    }
};
