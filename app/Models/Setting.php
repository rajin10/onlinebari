<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function getByName($name, $default = null)
    {
        return self::cached()[$name] ?? $default;
    }

    public static function cached()
    {
        return Cache::rememberForever('settings', function () {
            return self::all()->pluck('value', 'name')->toArray();
        });
    }

    // clear cache on update/delete
    public static function booted(): void
    {
        static::saved(function (self $model) {
            Cache::forget('settings');
            $model->cached();
        });

        static::deleted(function (self $model) {
            Cache::forget('settings');
            $model->cached();
        });
    }
}
