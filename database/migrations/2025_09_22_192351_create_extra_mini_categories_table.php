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
        Schema::create('extra_mini_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mini_category_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('cover_photo')->default('default.png');
            $table->boolean('status')->default(true);
            $table->boolean('is_feature')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_mini_categories');
    }
};
