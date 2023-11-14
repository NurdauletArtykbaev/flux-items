<?php

namespace Nurdaulet\FluxItems\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductRentTypePricesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'price' => (int)$this->price,
            'value' => $this->value,
            'weekend_price' => (int)$this->weekend_price,
            'from' => $this->from,
            'to' => $this->to,
        ];
    }
}
