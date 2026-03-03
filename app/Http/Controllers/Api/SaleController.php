<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleRequest;
use App\Http\Resources\SalesCollection;
use App\Models\Sale;

class SaleController extends Controller
{
    public function index(SaleRequest $request)
    {
        $sales = Sale::whereBetween('created_at', [$request->dateFrom, $request->dateTo])
                     ->paginate($request->limit ?? 500);

        return new SalesCollection($sales);
    }
}