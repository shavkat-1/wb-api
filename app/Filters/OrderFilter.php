<?php

namespace App\Filters;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends BaseFilter
{
    protected static function buildQuery(FormRequest $request): Builder
    {
        return Order::query()
            ->whereBetween('order_date', [
                $request->input('dateFrom') . ' 00:00:00',
                $request->input('dateTo')   . ' 23:59:59',
            ])
            ->orderBy('date');
    }
}