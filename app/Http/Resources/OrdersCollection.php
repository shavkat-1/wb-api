<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrdersCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(fn($order) => [
            'id'               => $order->id,
            'g_number'         => $order->g_number,
            'date'             => $order->date?->format('Y-m-d H:i:s'),
            'last_change_date' => $order->last_change_date,
            'supplier_article' => $order->supplier_article,
            'tech_size'        => $order->tech_size,
            'barcode'          => $order->barcode,
            'total_price'      => $order->total_price,
            'discount_percent' => $order->discount_percent,
            'warehouse_name'   => $order->warehouse_name,
            'oblast'           => $order->oblast,
            'income_id'        => $order->income_id,
            'odid'             => $order->odid,
            'nm_id'            => $order->nm_id,
            'subject'          => $order->subject,
            'category'         => $order->category,
            'brand'            => $order->brand,
            'is_cancel'        => $order->is_cancel,
            'cancel_dt'        => $order->cancel_dt?->format('Y-m-d H:i:s'),
        ])->all();
    }
}