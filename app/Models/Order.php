<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'table_id',
        'customer_name',
        'customer_phone',
        'order_type',
        'order_source',
        'status',
        'payment_method',
        'payment_status',
        'total_amount',
        'razorpay_order_id',
        'razorpay_payment_id',
        'notes'
    ];

    public function table()
    {
        return $this->belongsTo(ParlourTable::class, 'table_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    public function getNextStatus()
    {
        $flow = [
            'DINEIN' => [
                'PENDING'   => 'PREPARING',
                'PREPARING' => 'SERVED'
            ],
            'PARCEL' => [
                'PENDING'   => 'PREPARING',
                'PREPARING' => 'READY',
                'READY'     => 'PICKEDUP'
            ],
            'WALKIN' => [
                'PENDING'   => 'PREPARING',
                'PREPARING' => 'SERVED'
            ]
        ];

        return $flow[$this->order_type][$this->status] ?? null;
    }
}