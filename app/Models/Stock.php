<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'stock_id',
        'date',
        'warehouse',
        'product_name',
        'quantity',
    ];  
}
