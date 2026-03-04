<?php

namespace App\Filters;

use App\Models\Stock;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Builder;

class StockFilter extends BaseFilter
{
    protected static function buildQuery(FormRequest $request): Builder
    {
        return Stock::query()
            ->whereDate('date', $request->input('date'))
            ->orderBy('date');
    }
}
