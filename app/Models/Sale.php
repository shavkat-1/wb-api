<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'sale_id',
        'date',
        'product_name',
        'sku',
        'quantity',
        'amount',
        'warehouse',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];
}