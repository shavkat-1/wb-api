<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StocksCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(fn($stock) => [
            'id'           => $stock->id,
            'stock_id'     => $stock->stock_id,
            'date'         => $stock->date?->format('Y-m-d H:i:s'),
            'warehouse'    => $stock->warehouse,
            'product_name' => $stock->product_name,
            'sku'          => $stock->sku,
            'quantity'     => $stock->quantity,
        ])->all();
    }
}