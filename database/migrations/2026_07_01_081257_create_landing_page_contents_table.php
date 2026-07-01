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
        Schema::create('landing_page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('page_slug')->index();          // e.g. lice-comb, rust-removals
            $table->string('section_key');                 // e.g. hero_image, demo_video
            $table->string('content_type')->default('text'); // image | youtube_url | text
            $table->text('value')->nullable();             // filename for images, URL for video, raw text otherwise
            $table->timestamps();

            $table->unique(['page_slug', 'section_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page_contents');
    }
};
