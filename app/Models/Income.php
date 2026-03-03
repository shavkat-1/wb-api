<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    // Доходы от продаж, возвратов, комиссий и т.д.
    protected $fillable = [
        'income_id',
        'date',
        'amount',
        'source',
    ];
}
