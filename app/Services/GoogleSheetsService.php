<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Appends successful orders (with fraud analytics) to a Google Sheet via a
 * deployed Apps Script Web App webhook.
 *
 * No Google OAuth / service-account is required — the script runs under the
 * sheet owner's account and simply accepts a POSTed JSON row. The integration
 * silently no-ops when no webhook URL is configured, and never throws into the
 * order flow (failures are logged only).
 *
 * Setup: docs/google-sheets-webhook.md
 */
class GoogleSheetsService
{
    public function enabled(): bool
    {
        // An explicit OFF toggle disables logging even if a URL is present.
        $toggle = setting('GOOGLE_SHEETS_ENABLED');
        if ($toggle !== null && in_array(strtolower((string) $toggle), ['0', 'false', 'off', 'no'], true)) {
            return false;
        }

        return ! empty($this->webhookUrl());
    }

    /**
     * Post a sample row so an admin can confirm the webhook works.
     */
    public function sendTest(): bool
    {
        $url = $this->webhookUrl();

        if (empty($url)) {
            return false;
        }

        try {
            $response = Http::asJson()
                ->timeout(15)
                ->withOptions(['verify' => false])
                ->post($url, [
                    'secret' => $this->secret(),
                    'order_id' => 'TEST-'.now()->format('YmdHis'),
                    'invoice' => 'TEST-INVOICE',
                    'timestamp' => now()->toDateTimeString(),
                    'full_name' => 'Test Customer',
                    'phone' => '01700000000',
                    'address' => 'Test address, Dhaka',
                    'products' => 'Test product x1',
                    'total_price' => 0,
                    'payment_method' => 'Cash on Delivery',
                    'total_orders' => 0,
                    'success_orders' => 0,
                    'pending_orders' => 0,
                    'cancelled_orders' => 0,
                    'success_rate' => 0,
                    'risk_level' => 'Low',
                ]);

            return $response->successful();
        } catch (Throwable $e) {
            Log::error('GoogleSheetsService sendTest failed', ['error' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Send one order row to the sheet. `order_id` is included so the Apps
     * Script can upsert and avoid duplicate rows.
     *
     * @param  array<string,mixed>  $fraud  Output of FraudCheckService::check()
     */
    public function logOrder(Order $order, array $fraud = []): bool
    {
        if (! $this->enabled()) {
            return false;
        }

        $url = $this->webhookUrl();

        try {
            $payload = $this->buildPayload($order, $fraud);

            $response = Http::asJson()
                ->timeout(15)
                ->withOptions(['verify' => false])
                ->post($url, $payload);

            if (! $response->successful()) {
                Log::warning('GoogleSheetsService non-2xx response', [
                    'order_id' => $order->order_id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (Throwable $e) {
            Log::error('GoogleSheetsService logOrder failed', [
                'order_id' => $order->order_id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * @param  array<string,mixed>  $fraud
     * @return array<string,mixed>
     */
    private function buildPayload(Order $order, array $fraud): array
    {
        $order->loadMissing('orderDetails');

        $products = $order->orderDetails
            ->map(fn ($d) => trim(sprintf('%s x%d', $d->title, $d->qty)))
            ->implode(' | ');

        $address = trim(implode(', ', array_filter([
            $order->address,
            $order->thana,
            $order->district,
            $order->town,
        ])));

        return [
            'secret' => $this->secret(),
            'order_id' => $order->order_id,
            'invoice' => $order->invoice,
            'timestamp' => optional($order->created_at)->toDateTimeString(),
            'full_name' => $order->first_name,
            'phone' => $order->phone,
            'address' => $address,
            'products' => $products,
            'total_price' => (float) $order->total,
            'payment_method' => $order->payment_method,
            // Fraud analytics columns
            'total_orders' => $fraud['total'] ?? null,
            'success_orders' => $fraud['success'] ?? null,
            'pending_orders' => $fraud['pending'] ?? null,
            'cancelled_orders' => $fraud['cancelled'] ?? null,
            'success_rate' => $fraud['success_rate'] ?? null,
            'risk_level' => $fraud['risk_level'] ?? null,
        ];
    }

    private function webhookUrl(): ?string
    {
        // DB setting wins so it can be managed without a redeploy.
        $url = setting('GOOGLE_SHEETS_WEBHOOK_URL');

        return $url ?: config('services.google_sheets.webhook_url');
    }

    private function secret(): ?string
    {
        $secret = setting('GOOGLE_SHEETS_SECRET');

        return $secret ?: config('services.google_sheets.secret');
    }
}
