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
        Schema::table('attribute_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->after('id');
            $table->unsignedBigInteger('attribute_value_id')->after('product_id');

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attribute_product', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['attribute_value_id']);
            $table->dropColumn(['product_id', 'attribute_value_id']);
        });
    }
};
