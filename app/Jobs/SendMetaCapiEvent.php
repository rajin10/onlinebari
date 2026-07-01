<?php

namespace App\Jobs;

use App\Services\MetaCapiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * POSTs one or more events to the Meta Conversions API.
 * Dispatched after the response (or on a real queue if one is configured),
 * so a slow/failing Graph API call never affects the customer.
 */
class SendMetaCapiEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

    /**
     * @param  array<int, array<string, mixed>>  $events
     */
    public function __construct(public array $events) {}

    public function handle(): void
    {
        $pixelId = config('tracking.capi.pixel_id');
        $token = config('tracking.capi.access_token');

        if (! $pixelId || ! $token || empty($this->events)) {
            return;
        }

        $payload = ['data' => $this->events];
        if ($testCode = config('tracking.capi.test_code')) {
            $payload['test_event_code'] = $testCode;
        }

        $url = sprintf(
            'https://graph.facebook.com/%s/%s/events?access_token=%s',
            MetaCapiService::API_VERSION,
            $pixelId,
            $token
        );

        try {
            $response = Http::asJson()->timeout(10)->post($url, $payload);

            if ($response->failed()) {
                Log::warning('Meta CAPI event rejected', [
                    'status' => $response->status(),
                    'body' => $response->json() ?? $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Meta CAPI request failed', ['error' => $e->getMessage()]);
        }
    }
}
