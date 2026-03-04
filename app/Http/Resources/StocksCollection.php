<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StocksCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(fn($stock) => [
            'id'                 => $stock->id,
            'date'               => $stock->date,
            'last_change_date'   => $stock->last_change_date,
            'supplier_article'   => $stock->supplier_article,
            'tech_size'          => $stock->tech_size,
            'barcode'            => $stock->barcode,
            'quantity'           => $stock->quantity,
            'is_supply'          => $stock->is_supply,
            'is_realization'     => $stock->is_realization,
            'quantity_full'      => $stock->quantity_full,
            'warehouse_name'     => $stock->warehouse_name,
            'in_way_to_client'   => $stock->in_way_to_client,
            'in_way_from_client' => $stock->in_way_from_client,
            'nm_id'              => $stock->nm_id,
            'subject'            => $stock->subject,
            'category'           => $stock->category,
            'brand'              => $stock->brand,
            'sc_code'            => $stock->sc_code,
            'price'              => $stock->price,
            'discount'           => $stock->discount,
        ])->all();
    }
}