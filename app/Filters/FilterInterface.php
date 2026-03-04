<?php

namespace App\Filters;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface FilterInterface
{
    public static function searchByRequest(FormRequest $request): LengthAwarePaginator;
}