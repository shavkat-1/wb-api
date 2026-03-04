<?php

namespace App\Filters;

use App\Models\Sale;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Builder;

class SaleFilter extends BaseFilter
{
    protected static function buildQuery(FormRequest $request): Builder
    {
        return Sale::query()
            ->whereBetween('date', [
                $request->input('dateFrom') . ' 00:00:00',
                $request->input('dateTo')   . ' 23:59:59',
            ])
            ->orderBy('date');
    }
}