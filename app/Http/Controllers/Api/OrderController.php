<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrdersCollection;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(OrderRequest $request)
    {
        $orders = Order::whereBetween('created_at', [$request->dateFrom, $request->dateTo])
                       ->paginate($request->limit ?? 500);

        return new OrdersCollection($orders);
    }
}