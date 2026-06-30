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
        Schema::create('shop_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_admin')->default(0);
            $table->string('name');
            $table->string('gmail');
            $table->text('selfi')->nullable();
            $table->string('slug')->nullable();
            $table->string('address');
            $table->string('url')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('holder_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('routing')->nullable();
            $table->string('profile');
            $table->string('cover_photo');
            $table->text('description');
            $table->decimal('commission', 10, 0)->nullable();
            $table->string('nid')->nullable();
            $table->text('nidback')->nullable();
            $table->string('trade')->nullable();
            $table->integer('nogod')->nullable();
            $table->integer('rocket')->nullable();
            $table->integer('bkash')->nullable();
            $table->text('mobile')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_infos');
    }
};
