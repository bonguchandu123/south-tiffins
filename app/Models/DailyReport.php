<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $fillable = [
        'report_date',
        'total_orders',
        'dinein_orders',
        'parcel_orders',
        'walkin_orders',
        'total_revenue',
        'cash_revenue',
        'online_revenue'
    ];

    protected $casts = [
        'report_date' => 'date'
    ];
}
