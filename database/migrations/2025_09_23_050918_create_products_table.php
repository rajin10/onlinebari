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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('author_id')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->longText('spec')->nullable();
            $table->string('regular_price', 111);
            $table->string('whole_price', 111)->nullable();
            $table->string('buying_price', 111)->nullable();
            $table->integer('dis_type')->nullable();
            $table->string('discount_price', 111)->nullable();
            $table->integer('quantity');
            $table->string('unit')->nullable();
            $table->string('image')->default('');
            $table->boolean('shipping_charge');
            $table->integer('point')->default(0);
            $table->integer('reach')->default(0);
            $table->boolean('status');
            $table->integer('is_aproved')->default(0);
            $table->integer('type')->default(0);
            $table->boolean('download_able')->default(0);
            $table->integer('download_limit')->nullable();
            $table->date('download_expire')->nullable();
            $table->integer('sheba')->nullable();
            $table->integer('book')->nullable();
            $table->string('isbn', 222)->nullable();
            $table->string('edition')->nullable();
            $table->string('pages')->nullable();
            $table->string('country')->nullable();
            $table->string('language')->nullable();
            $table->string('book_file')->nullable();
            $table->integer('refer')->default(0);
            $table->string('video')->nullable();
            $table->string('video_thumb')->nullable();
            $table->string('yvideo')->nullable();
            $table->string('sku')->nullable();
            $table->string('prdct_extra_msg')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
