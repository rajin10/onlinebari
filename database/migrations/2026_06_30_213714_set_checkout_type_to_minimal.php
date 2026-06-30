<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Activate the premium minimal checkout (Name / Phone / Address) by setting
 * CHECKOUT_TYPE = 0 so the storefront serves the redesigned c_minimal /
 * bc_minimal views. Idempotent — can still be changed from the admin later.
 */
return new class extends Migration
{
    public function up(): void
    {
        Setting::updateOrCreate(['name' => 'CHECKOUT_TYPE'], ['value' => '0']);

        // The settings helper caches forever; ensure the new value is picked up.
        Cache::forget('settings');
    }

    public function down(): void
    {
        // No-op: this is a one-way preference change. Revert from the admin if needed.
    }
};
