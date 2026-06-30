<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncompleteLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'name',
        'phone',
        'email',
        'address',
        'page_url',
        'ip_address',
        'cart_data',
        'subtotal',
        'total_items',
        'last_activity',
        'converted'
    ];

    protected $casts = [
        'cart_data' => 'array',
        'last_activity' => 'datetime',
        'converted' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method to get formatted cart items
    public function getCartItems()
    {
        return $this->cart_data ? collect($this->cart_data) : collect([]);
    }
}