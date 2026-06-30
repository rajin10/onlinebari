<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\FraudCheckService;
use App\Services\GoogleSheetsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Post-order intelligence: runs the fraud/courier-history check, persists the
 * derived analytics + risk level on the order, applies risk-based handling
 * (flag High-risk orders for admin review), and logs the order to Google
 * Sheets with the fraud columns.
 *
 * Dispatched with ->afterResponse() so it never blocks the checkout response
 * and does not require a running queue worker. All failures are swallowed so
 * they can never break an order that was already placed.
 */
class ProcessOrderIntelligence implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(public int $orderId) {}

    public function handle(FraudCheckService $fraud, GoogleSheetsService $sheets): void
    {
        $order = Order::with('orderDetails')->find($this->orderId);

        if (! $order) {
            return;
        }

        $result = [];

        // 1) Fraud / courier-history intelligence.
        try {
            $result = $fraud->check($order->phone);

            $isHigh = ($result['risk_level'] ?? null) === FraudCheckService::RISK_HIGH;

            $order->forceFill([
                'fraud_total_orders' => $result['total'],
                'fraud_success_orders' => $result['success'],
                'fraud_pending_orders' => $result['pending'],
                'fraud_cancelled_orders' => $result['cancelled'],
                'fraud_success_rate' => $result['success_rate'],
                'fraud_risk_level' => $result['risk_level'],
                'is_flagged' => $isHigh,
                'fraud_checked_at' => now(),
            ])->save();
        } catch (Throwable $e) {
            Log::error('ProcessOrderIntelligence fraud step failed', [
                'order_id' => $order->order_id,
                'error' => $e->getMessage(),
            ]);
        }

        // 2) Google Sheets logging (with fraud columns).
        try {
            $sheets->logOrder($order, $result);
        } catch (Throwable $e) {
            Log::error('ProcessOrderIntelligence sheets step failed', [
                'order_id' => $order->order_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
