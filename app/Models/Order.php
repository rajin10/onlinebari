<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'company_name', 'country', 'address', 'town', 
        'district', 'thana', 'post_code', 'phone', 'email', 'shipping_method', 
        'shipping_charge', 'single_charge', 'payment_method', 'mobile_number', 
        'transaction_id', 'coupon_code', 'subtotal', 'discount', 'total', 
        'cart_type', 'pos_order', 'status', 'pay_staus', 'pay_date', 'order_id', 'invoice'
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commission()
    {
        return $this->hasOne(Commission::class, 'order_id');
    }

}
