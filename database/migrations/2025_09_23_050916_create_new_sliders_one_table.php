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
        Schema::create('new_sliders_one', function (Blueprint $table) {
            $table->id('nsid');
            $table->string('image');
            $table->string('url')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('is_feature')->default(0);
            $table->integer('is_pop')->default(0);
            $table->integer('is_sub')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_sliders_one');
    }
};
