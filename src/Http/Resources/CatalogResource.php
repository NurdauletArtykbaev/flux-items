<?php

namespace Nurdaulet\FluxItems\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Nurdaulet\FluxItems\Http\Resources\Product\TestProductsResource;

class CatalogResource extends JsonResource
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
            'items' => $this->whenLoaded('items', function () {
                return TestProductsResource::collection($this->items);
            })
        ];
    }
}
