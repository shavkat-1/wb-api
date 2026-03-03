<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IncomeRequest;
use App\Http\Resources\IncomesCollection;
use App\Models\Income;

class IncomeController extends Controller
{
    public function index(IncomeRequest $request)
    {
        $incomes = Income::whereBetween('created_at', [$request->dateFrom, $request->dateTo])
                         ->paginate($request->limit ?? 500);

        return new IncomesCollection($incomes);
    }
}