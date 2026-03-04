<?php

namespace App\Http\Controllers\Api;

use App\Filters\StockFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StockRequest;
use App\Http\Resources\StocksCollection;

class StockController extends Controller
{
    public function index(StockRequest $request): StocksCollection
    {
        return new StocksCollection(
            StockFilter::searchByRequest($request)
        );
    }
}