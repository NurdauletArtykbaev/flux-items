<?php

namespace Nurdaulet\FluxItems\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReceiveMethodsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        dd($this);
        return [
            'id' => $this->id,
            'name' => $this->name,
            "delivery_price" => 0
//            'pivot' => $this->name,
        ];
    }
}
