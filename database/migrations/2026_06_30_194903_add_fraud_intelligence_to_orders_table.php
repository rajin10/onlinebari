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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('fraud_total_orders')->nullable()->after('cart_type');
            $table->unsignedInteger('fraud_success_orders')->nullable()->after('fraud_total_orders');
            $table->unsignedInteger('fraud_pending_orders')->nullable()->after('fraud_success_orders');
            $table->unsignedInteger('fraud_cancelled_orders')->nullable()->after('fraud_pending_orders');
            $table->decimal('fraud_success_rate', 5, 2)->nullable()->after('fraud_cancelled_orders');
            $table->string('fraud_risk_level', 20)->nullable()->after('fraud_success_rate');
            $table->boolean('is_flagged')->default(false)->after('fraud_risk_level');
            $table->timestamp('fraud_checked_at')->nullable()->after('is_flagged');

            $table->index('fraud_risk_level');
            $table->index('is_flagged');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['fraud_risk_level']);
            $table->dropIndex(['is_flagged']);
            $table->dropColumn([
                'fraud_total_orders',
                'fraud_success_orders',
                'fraud_pending_orders',
                'fraud_cancelled_orders',
                'fraud_success_rate',
                'fraud_risk_level',
                'is_flagged',
                'fraud_checked_at',
            ]);
        });
    }
};
