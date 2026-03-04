<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class IncomesCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(fn($income) => [
            'id'               => $income->id,
            'income_id'        => $income->income_id,
            'number'           => $income->number,
            'date'             => $income->date,
            'last_change_date' => $income->last_change_date,
            'supplier_article' => $income->supplier_article,
            'tech_size'        => $income->tech_size,
            'barcode'          => $income->barcode,
            'quantity'         => $income->quantity,
            'total_price'      => $income->total_price,
            'date_close'       => $income->date_close,
            'warehouse_name'   => $income->warehouse_name,
            'nm_id'            => $income->nm_id,
        ])->all();
    }
}