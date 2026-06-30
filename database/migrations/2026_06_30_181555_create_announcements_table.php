<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('message');                   // headline text (supports Bangla)
            $table->string('icon')->nullable();          // whatsapp | phone | offer | fire | truck | shield | star | none
            $table->string('cta_text')->nullable();      // button label e.g. "Order on WhatsApp"
            $table->string('cta_link')->nullable();      // url / tel: / wa.me link
            $table->string('urgency_label')->nullable(); // e.g. "Limited Time", "Almost Gone"
            $table->string('bg_color')->nullable();      // optional per-item background override
            $table->string('text_color')->nullable();    // optional per-item text override
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed one premium default announcement so the bar is live immediately.
        DB::table('announcements')->insert([
            'message' => 'আমাদের যেকোনো প্রোডাক্ট অর্ডার করতে কল করুন বা WhatsApp এ মেসেজ দিন 01624109210',
            'icon' => 'whatsapp',
            'cta_text' => 'Order on WhatsApp',
            'cta_link' => 'https://wa.me/8801624109210',
            'is_active' => true,
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
