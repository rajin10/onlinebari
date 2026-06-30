<?php

namespace App\Services;

use App\Models\Order;
use App\Support\BdPhone;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Courier-history based fraud intelligence for a phone number.
 *
 * Wraps the BD Courier API (also used by the admin fraud checker) and merges
 * in this shop's own local order history, then derives a success rate and a
 * Low / Medium / High risk level.
 */
class FraudCheckService
{
    /** Fallback key kept identical to the existing admin fraud checker. */
    private const DEFAULT_KEY = 'ZkEEfBAEBRxVkgcLpR3Z5e3sPHQ6dy0XViGTqYyg4clRjj06rRKmAs41Smp2';

    public const RISK_LOW = 'Low';

    public const RISK_MEDIUM = 'Medium';

    public const RISK_HIGH = 'High';

    /**
     * Build the full fraud profile for a phone number.
     *
     * @return array{
     *   total:int, success:int, pending:int, cancelled:int,
     *   success_rate:float, risk_level:string, source:string, error:?string,
     *   couriers:array<string,array{total:int,success:int,cancelled:int}>
     * }
     */
    public function check(?string $phone): array
    {
        $normalized = BdPhone::normalize($phone) ?? (string) $phone;

        $couriers = [];
        $apiTotal = $apiSuccess = $apiCancel = 0;
        $error = null;
        $source = 'local';

        try {
            $apiData = $this->callApi($normalized);

            if ($apiData['ok']) {
                $source = 'bdcourier+local';
                $couriers = $apiData['couriers'];
                $apiTotal = $apiData['total'];
                $apiSuccess = $apiData['success'];
                $apiCancel = $apiData['cancelled'];
            } else {
                $error = $apiData['error'];
            }
        } catch (Throwable $e) {
            $error = $e->getMessage();
            Log::warning('FraudCheckService API failure', ['phone' => $normalized, 'error' => $error]);
        }

        // Merge local shop history (robust suffix match across stored formats).
        $local = $this->localHistory($normalized);

        $total = $apiTotal + $local['total'];
        $success = $apiSuccess + $local['success'];
        $cancelled = $apiCancel + $local['cancelled'];
        $pending = max(0, $total - $success - $cancelled);

        $successRate = $total > 0 ? round(($success / $total) * 100, 2) : 0.0;

        return [
            'total' => $total,
            'success' => $success,
            'pending' => $pending,
            'cancelled' => $cancelled,
            'success_rate' => $successRate,
            'risk_level' => $this->riskLevel($total, $successRate),
            'source' => $source,
            'error' => $error,
            'couriers' => $couriers,
        ];
    }

    /**
     * @return array{ok:bool, error:?string, total:int, success:int, cancelled:int, couriers:array<string,array<string,int>>}
     */
    private function callApi(string $phone): array
    {
        $key = setting('BDCOURIER_API_KEY')
            ?: config('services.bdcourier.key')
            ?: self::DEFAULT_KEY;

        $url = config('services.bdcourier.url', 'https://bdcourier.com/api/courier-check');

        $response = Http::withToken($key)
            ->acceptJson()
            ->timeout(10)
            ->withOptions(['verify' => false])
            ->get($url, ['phone' => $phone]);

        if (! $response->ok()) {
            return ['ok' => false, 'error' => 'API HTTP '.$response->status(), 'total' => 0, 'success' => 0, 'cancelled' => 0, 'couriers' => []];
        }

        $data = $response->json();

        if (! is_array($data) || ! isset($data['courierData'])) {
            $message = $data['error'] ?? $data['message'] ?? 'Invalid API response structure';

            return ['ok' => false, 'error' => $message, 'total' => 0, 'success' => 0, 'cancelled' => 0, 'couriers' => []];
        }

        $couriers = [];
        foreach (['steadfast', 'pathao', 'redx', 'paperfly'] as $name) {
            $couriers[$name] = [
                'total' => (int) ($data['courierData'][$name]['total_parcel'] ?? 0),
                'success' => (int) ($data['courierData'][$name]['success_parcel'] ?? 0),
                'cancelled' => (int) ($data['courierData'][$name]['cancelled_parcel'] ?? 0),
            ];
        }

        return [
            'ok' => true,
            'error' => null,
            'total' => (int) ($data['courierData']['summary']['total_parcel'] ?? 0),
            'success' => (int) ($data['courierData']['summary']['success_parcel'] ?? 0),
            'cancelled' => (int) ($data['courierData']['summary']['cancelled_parcel'] ?? 0),
            'couriers' => $couriers,
        ];
    }

    /**
     * @return array{total:int, success:int, cancelled:int}
     */
    private function localHistory(string $phone): array
    {
        $suffix = substr($phone, -10);

        $orders = Order::where('phone', 'like', '%'.$suffix)->get(['status']);

        return [
            'total' => $orders->count(),
            'success' => $orders->where('status', 3)->count(), // 3 = Delivered
            'cancelled' => $orders->where('status', 2)->count(), // 2 = Cancelled
        ];
    }

    private function riskLevel(int $total, float $successRate): string
    {
        // No history anywhere -> treat as Low (no negative signal yet).
        if ($total === 0) {
            return self::RISK_LOW;
        }

        if ($successRate >= 70) {
            return self::RISK_LOW;
        }

        if ($successRate >= 50) {
            return self::RISK_MEDIUM;
        }

        return self::RISK_HIGH;
    }
}
