<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'bill_number',
        'order_id',
        'total_amount',
        'payment_method',
        'payment_status',
        'printed',
        'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function generateBillNumber()
    {
        return 'BILL' . date('ymd') . mt_rand(100, 999);
    }
}