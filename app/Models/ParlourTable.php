<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParlourTable extends Model
{
    protected $fillable = [
        'table_number',
        'qr_code_url',
        'is_active'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'table_id');
    }
}