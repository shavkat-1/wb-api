<?php

namespace App\Filters;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseFilter implements FilterInterface
{
    protected const DEFAULT_LIMIT = 500;

    /**
     * Построение запроса — реализуется в каждом дочернем фильтре
     */
    abstract protected static function buildQuery(FormRequest $request): Builder;

    /**
     * Общая логика: пагинация, лимит, appends
     */
    public static function searchByRequest(FormRequest $request): LengthAwarePaginator
    {
        return static::buildQuery($request)
            ->paginate($request->integer('limit', static::DEFAULT_LIMIT))
            ->appends($request->except('page'));
    }
}