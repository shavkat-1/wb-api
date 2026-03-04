<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'income_id',
        'date',
        'amount',
        'source',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];
}