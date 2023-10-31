<?php

namespace Nurdaulet\FluxItems\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductUserResource extends JsonResource
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
            'surname' => $this->surname,
            'last_name' => $this->last_name,
            'company_name' => $this->company_name,
            'avg_rating' => $this->avg_rating,
            'phone' => $this->phone,
            'is_verified' => $this->is_verified,
            'is_identified' => $this->is_identified,
//            'logo_webp' => $this->logo_webp,
            'avatar_url' => $this->avatar_url,
            'avatar_color' => $this->avatar_color,
            'last_online' => $this->last_online,
            'graphic_works' => $this->graphic_works,
        ];
    }
}
