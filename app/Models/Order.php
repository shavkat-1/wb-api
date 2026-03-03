<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'order_date',
        'customer_name',
        'total_amount',
        'status',
    ];
}
