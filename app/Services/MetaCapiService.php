<?php

namespace App\Services;

use App\Jobs\SendMetaCapiEvent;
use App\Models\Order;
use App\Support\Tracking;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Meta Conversions API (server-side) sender.
 *
 * Fires the same conversion event server → Meta that the browser Pixel fires,
 * reusing the deterministic `event_id` so Meta deduplicates the two hits.
 * Everything is a no-op until META_CAPI_ENABLED + pixel id + token are set,
 * so this is safe to leave wired in production.
 */
class MetaCapiService
{
    public const API_VERSION = 'v19.0';

    public static function enabled(): bool
    {
        return (bool) config('tracking.capi.enabled')
            && filled(config('tracking.capi.pixel_id'))
            && filled(config('tracking.capi.access_token'));
    }

    /**
     * Queue the Purchase event for an order. Runs after the HTTP response
     * (no queue worker required) so checkout is never blocked, and is
     * guarded so a page refresh does not re-send within 6 hours.
     */
    public static function queuePurchase(Order $order): void
    {
        if (! self::enabled()) {
            return;
        }

        // Tracking must NEVER break the order-success page: swallow everything.
        try {
            // One send per order (Meta also dedupes by event_id, this just avoids noise).
            if (! Cache::add('capi_purchase.'.$order->id, 1, now()->addHours(6))) {
                return;
            }

            SendMetaCapiEvent::dispatchAfterResponse([self::purchaseEvent($order)]);
        } catch (\Throwable $e) {
            Log::warning('Meta CAPI queuePurchase failed', ['error' => $e->getMessage()]);
        }
    }

    protected static function purchaseEvent(Order $order): array
    {
        $items = collect(Tracking::orderItems($order))->map(fn ($i) => [
            'id' => $i['item_id'],
            'quantity' => $i['quantity'],
            'item_price' => $i['price'],
        ])->all();

        return [
            'event_name' => 'Purchase',
            'event_time' => now()->timestamp,
            'event_id' => Tracking::purchaseEventId($order), // == browser Pixel eventID
            'event_source_url' => route('order.success', $order->order_id),
            'action_source' => 'website',
            'user_data' => self::userData($order),
            'custom_data' => [
                'currency' => Tracking::currency(),
                'value' => (float) $order->total,
                'content_type' => 'product',
                'contents' => $items,
                'num_items' => (int) $order->orderDetails->sum('qty'),
                'order_id' => $order->order_id,
            ],
        ];
    }

    /**
     * Advanced-matching signals. PII is SHA-256 hashed (normalised first);
     * IP/UA/fbp/fbc are sent raw as Meta requires.
     */
    protected static function userData(Order $order): array
    {
        $data = array_filter([
            'em' => self::hash($order->email),
            'ph' => self::hash(self::normalizePhone($order->phone)),
            'fn' => self::hash($order->first_name),
            'ln' => self::hash($order->last_name),
            'ct' => self::hash($order->town),
            'zp' => self::hash($order->post_code),
        ]);

        if ($request = request()) {
            $data['client_ip_address'] = $request->ip();
            $data['client_user_agent'] = $request->userAgent();
            if ($fbp = $request->cookie('_fbp')) {
                $data['fbp'] = $fbp;
            }
            if ($fbc = $request->cookie('_fbc')) {
                $data['fbc'] = $fbc;
            }
        }

        return $data;
    }

    protected static function hash(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : hash('sha256', mb_strtolower($value));
    }

    /** Normalise a BD phone number to country-code digits (8801XXXXXXXXX). */
    protected static function normalizePhone(?string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);
        if ($digits === '') {
            return null;
        }
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }
        if (str_starts_with($digits, '0')) {
            $digits = '88'.$digits;
        } elseif (! str_starts_with($digits, '88')) {
            $digits = '88'.$digits;
        }

        return $digits;
    }
}
