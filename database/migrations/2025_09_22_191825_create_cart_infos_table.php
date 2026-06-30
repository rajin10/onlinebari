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
        Schema::create('cart_infos', function (Blueprint $table) {
            $table->id();
            $table->text('ser')->nullable();
            $table->foreignId('user_id');
            $table->integer('vendor')->nullable();
            $table->foreignId('product_id');
            $table->string('qty');
            $table->string('price');
            $table->string('weight')->default('0');
            $table->string('color')->nullable();
            $table->string('attr')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_infos');
    }
};
