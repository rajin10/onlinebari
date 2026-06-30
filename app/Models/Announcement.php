<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Announcement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'sort_order' => 'integer',
    ];

    /**
     * Only announcements that are enabled and inside their (optional) schedule window.
     */
    public function scopeLive(Builder $query): Builder
    {
        $now = now();

        return $query->where('is_active', true)
            ->where(fn (Builder $q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn (Builder $q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now))
            ->orderBy('sort_order')
            ->orderByDesc('id');
    }

    /**
     * Cached collection of currently-visible announcements for the storefront.
     * Cache is short-lived so scheduled start/end windows stay accurate.
     */
    public static function visible()
    {
        return Cache::remember('announcements.visible', now()->addMinutes(5), function () {
            return static::query()->live()->get();
        });
    }

    protected static function booted(): void
    {
        $flush = fn () => Cache::forget('announcements.visible');

        static::saved($flush);
        static::deleted($flush);
    }
}
