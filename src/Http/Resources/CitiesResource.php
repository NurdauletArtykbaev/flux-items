<?php

namespace Nurdaulet\FluxItems\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CitiesResource extends JsonResource
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
            'id' => (int) $this->id,
            'name' => (string) $this->name,
            'lat' => (string) $this->lat,
            'lng' => (string) $this->lng,
            'is_current' => (bool) $this->id == $request->currentCityId,
        ];
    }
}
