<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrdersCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(fn($order) => [
            'id'            => $order->id,
            'order_id'      => $order->order_id,
            'sku'           => $order->sku,
            'order_date'    => $order->order_date?->format('Y-m-d H:i:s'),
            'customer_name' => $order->customer_name,
            'total_amount'  => $order->total_amount,
            'status'        => $order->status,
        ])->all();
    }
}