<?php

namespace Nurdaulet\FluxItems\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
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
            "id" => $this->id,
            "city_id" => $this->city_id,
            "user_id" => $this->user_id,
            "name" => $this->name,
            "address" => $this->address,
            "house" => $this->house,
            "floor" => $this->floor,
            "apartment" => $this->apartment,
            "lat"  => (string) $this->lat,
            "lng"  => (string) $this->lng,
            "is_type_store" => $this->is_type_store,
            "is_main" => $this->is_main
        ];
    }
}
