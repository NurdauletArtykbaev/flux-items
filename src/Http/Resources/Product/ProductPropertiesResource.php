<?php

namespace Nurdaulet\FluxItems\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductPropertiesResource extends JsonResource
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
            'value_id' => $this->pivot->value?->id,
            'value_name' => $this->pivot->value?->value,
            'custom_value' => $this->pivot->custom_value,
        ];
    }
}
