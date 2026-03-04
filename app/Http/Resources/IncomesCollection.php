<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class IncomesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
      return $this->collection->map(fn($income) => [
            'id'         => $income->id,
            'income_id' => $income->income_id,
            'source'     => $income->source,
            'amount'     => $income->amount,
            'date'       => $income->date->format('Y-m-d'),
        ])->all();
    }
}
