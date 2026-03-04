<?php

namespace App\Filters;

use App\Models\Income;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Builder;

class IncomeFilter extends BaseFilter
{
    protected static function buildQuery(FormRequest $request): Builder
    {
        return Income::query()
            ->whereBetween('date', [
                $request->input('dateFrom') . ' 00:00:00',
                $request->input('dateTo')   . ' 23:59:59',
            ])
            ->orderBy('date');
    }
}