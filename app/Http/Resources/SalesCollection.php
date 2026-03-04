<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SalesCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(fn($sale) => [
            'id'                  => $sale->id,
            'sale_id'             => $sale->sale_id,
            'g_number'            => $sale->g_number,
            'date'                => $sale->date?->format('Y-m-d H:i:s'),
            'last_change_date'    => $sale->last_change_date,
            'supplier_article'    => $sale->supplier_article,
            'tech_size'           => $sale->tech_size,
            'barcode'             => $sale->barcode,
            'total_price'         => $sale->total_price,
            'discount_percent'    => $sale->discount_percent,
            'is_supply'           => $sale->is_supply,
            'is_realization'      => $sale->is_realization,
            'promo_code_discount' => $sale->promo_code_discount,
            'warehouse_name'      => $sale->warehouse_name,
            'country_name'        => $sale->country_name,
            'oblast_okrug_name'   => $sale->oblast_okrug_name,
            'region_name'         => $sale->region_name,
            'income_id'           => $sale->income_id,
            'odid'                => $sale->odid,
            'spp'                 => $sale->spp,
            'for_pay'             => $sale->for_pay,
            'finished_price'      => $sale->finished_price,
            'price_with_disc'     => $sale->price_with_disc,
            'nm_id'               => $sale->nm_id,
            'subject'             => $sale->subject,
            'category'            => $sale->category,
            'brand'               => $sale->brand,
            'is_storno'           => $sale->is_storno,
        ])->all();
    }
}