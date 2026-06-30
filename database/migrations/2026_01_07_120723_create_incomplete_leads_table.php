<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('incomplete_leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->index();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('page_url')->nullable();
            $table->string('ip_address')->nullable();
            $table->json('cart_data')->nullable(); // Cart information store করার জন্য
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->integer('total_items')->default(0);
            $table->timestamp('last_activity')->nullable();
            $table->boolean('converted')->default(false); // Order complete হলে true
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('incomplete_leads');
    }
};