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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('first_name');
            $table->string('order_id')->nullable();
            $table->string('invoice')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('town')->nullable();
            $table->string('district')->nullable();
            $table->string('thana')->nullable();
            $table->text('meet_time')->nullable();
            $table->string('post_code')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('shipping_method')->nullable();
            $table->decimal('shipping_charge', 10, 2)->nullable();
            $table->decimal('single_charge', 10, 2)->default(0.00);
            $table->string('payment_method')->nullable();
            $table->string('refund_method')->nullable();
            $table->string('refund_amount')->nullable();
            $table->integer('pay_staus')->nullable();
            $table->string('pay_date', 11)->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('holder_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('coupon_code')->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->integer('point')->default(0);
            $table->boolean('is_pre')->default(0);
            $table->integer('status')->default(0);
            $table->integer('refer_bonus')->default(0);
            $table->integer('refer_id')->nullable();
            $table->string('cart_type')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
