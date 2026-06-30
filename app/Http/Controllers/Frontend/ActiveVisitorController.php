<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ActiveVisitor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActiveVisitorController extends Controller
{
    /**
     * A visitor counts as "active" when their last heartbeat landed within this
     * many seconds. The client beats every 5s, so this is the grace window that
     * keeps the live count stable between beats and forgives a missed ping or two.
     */
    private const ACTIVE_WINDOW_SECONDS = 60;

    public function heartbeat(Request $request)
    {
        $visitorId = $request->cookie('visitor_id');

        if (! $visitorId) {
            $visitorId = Str::uuid()->toString();
        }

        $visitor = ActiveVisitor::updateOrCreate(
            ['visitor_id' => $visitorId],
            [
                'user_id' => auth()->check() ? auth()->id() : null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'current_url' => $request->fullUrl(),
                'last_seen_at' => now(),
                'left_at' => null,
            ]
        );

        $activeCount = ActiveVisitor::whereNull('left_at')
            ->where('last_seen_at', '>=', now()->subSeconds(self::ACTIVE_WINDOW_SECONDS))
            ->count();

        return response()->json([
            'status' => true,
            'visitor_id' => $visitorId,
            'active_count' => $activeCount,
        ])->cookie('visitor_id', $visitorId, 60 * 24 * 30);
    }

    public function leave(Request $request)
    {
        $visitorId = $request->cookie('visitor_id');

        if ($visitorId) {
            ActiveVisitor::where('visitor_id', $visitorId)
                ->update([
                    'left_at' => now(),
                ]);
        }

        return response()->json(['status' => true]);
    }

    public function count()
    {
        $activeCount = ActiveVisitor::whereNull('left_at')
            ->where('last_seen_at', '>=', now()->subSeconds(self::ACTIVE_WINDOW_SECONDS))
            ->count();

        return response()->json([
            'status' => true,
            'active_count' => $activeCount,
        ]);
    }
}
