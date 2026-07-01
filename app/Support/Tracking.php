<?php

namespace App\Support;

/**
 * Central helper for GTM / dataLayer / Meta CAPI values.
 *
 * The purchase event_id MUST be identical on the browser (Pixel/GTM) and on
 * the server (Meta Conversion API) so Meta can deduplicate the two hits.
 * Deriving it deterministically from the order — instead of a random id —
 * guarantees both sides agree without persisting anything.
 */
class Tracking
{
    public static function gtmId(): ?string
    {
        return config('tracking.gtm_id');
    }

    public static function currency(): string
    {
        return config('tracking.currency', 'BDT');
    }

    /**
     * Deterministic event_id for an order's `purchase` event.
     * Use the SAME formula in the server-side CAPI call.
     */
    public static function purchaseEventId($order): string
    {
        $ref = $order->invoice ?: $order->id;

        return 'purchase.'.$ref;
    }

    /**
     * Map order line items into the GA4/Meta `items` shape used across the site.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function orderItems($order): array
    {
        return $order->orderDetails->map(function ($detail) {
            return [
                'item_id' => (string) $detail->product_id,
                'item_name' => $detail->title,
                'price' => (float) $detail->price,
                'quantity' => (int) $detail->qty,
            ];
        })->values()->all();
    }
}
