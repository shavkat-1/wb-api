<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockRequest;
use App\Http\Resources\StocksCollection;
use App\Models\Stock;
use Carbon\Carbon;

class StockController extends Controller
{
    public function index(StockRequest $request)
    {
        $date = Carbon::parse($request->dateFrom)->startOfDay();
        $stocks = Stock::whereDate('created_at', $date)
                       ->paginate($request->limit ?? 500);

        return new StocksCollection($stocks);
    }
}