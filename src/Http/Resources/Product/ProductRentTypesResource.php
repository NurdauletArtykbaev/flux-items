<?php

namespace Nurdaulet\FluxItems\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductRentTypesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this?->pivot->price,
            'old_price' => $this?->pivot->old_price
        ];
    }
}
