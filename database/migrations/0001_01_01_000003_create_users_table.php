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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->string('name', 50);
            $table->integer('refer')->default(2);
            $table->string('username', 25);
            $table->string('email')->nullable();
            $table->string('phone', 30);
            $table->string('password');
            $table->string('oauth_id')->nullable();
            $table->string('oauth_type')->nullable();
            $table->boolean('is_approved')->default(0);
            $table->boolean('status')->default(1);
            $table->integer('cancel_attempt')->default(0);
            $table->string('avatar')->default('default.png');
            $table->integer('point')->default(0);
            $table->integer('pen_point')->nullable();
            $table->date('joining_date');
            $table->string('joining_month', 15);
            $table->year('joining_year');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->integer('desig')->nullable();
            $table->integer('wallate')->default(0);
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
