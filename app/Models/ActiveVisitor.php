<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveVisitor extends Model
{
    protected $fillable = [
        'visitor_id',
        'user_id',
        'ip_address',
        'user_agent',
        'current_url',
        'last_seen_at',
        'left_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'left_at' => 'datetime',
    ];
}
