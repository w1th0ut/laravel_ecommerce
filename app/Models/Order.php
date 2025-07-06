<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $casts = [
        'billing_address' => 'array',
        'shipping_address' => 'array',
    ];
    // Di dalam class Order
    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'status',
        'billing_address',
        'shipping_address',
        'payment_method',
        'payment_status'
    ];
    public function orderItems()
    {
        return $this->hasMany(\App\Models\OrderItem::class);
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
