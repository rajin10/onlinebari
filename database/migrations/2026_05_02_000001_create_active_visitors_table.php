<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('active_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id', 100)->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('current_url')->nullable();
            $table->timestamp('last_seen_at')->nullable()->index();
            $table->timestamp('left_at')->nullable();
            $table->timestamps();

            $table->index(['visitor_id', 'last_seen_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('active_visitors');
    }
};