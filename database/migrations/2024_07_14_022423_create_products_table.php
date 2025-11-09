<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->double('price');
            $table->text('description');
            $table->string('entreprise');
            $table->integer('evaluation');
            $table->integer('prix_promo')->nullable();
            $table->integer('prix_livraison')->default(0);
            $table->text('info')->nullable();
            $table->string('photo_main');
            $table->string('photo_secondary')->nullable();
            $table->enum('personnalisable', ['no', 'yes'])->default('no');
            $table->enum('statut', ['in_stock', 'unavailable'])->default('in_stock');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
