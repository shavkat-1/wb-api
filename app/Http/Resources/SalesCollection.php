<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SalesCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(fn($sale) => [
            'id'           => $sale->id,
            'sale_id'      => $sale->sale_id,
            'date'         => $sale->date?->format('Y-m-d H:i:s'),
            'product_name' => $sale->product_name,
            'sku'          => $sale->sku,
            'quantity'     => $sale->quantity,
            'amount'       => $sale->amount,
            'warehouse'    => $sale->warehouse,
        ])->all();
    }
}