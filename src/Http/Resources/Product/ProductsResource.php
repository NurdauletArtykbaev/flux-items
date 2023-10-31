<?php

namespace Nurdaulet\FluxItems\Http\Resources\v2\Product;

use App\Http\Resources\v2\Category\CategoriesResource;
use App\Http\Resources\v2\Product\ProductImagesResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
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
            'is_hit' => $this->is_hit,
            'is_busy' => $this->is_busy,
//            'city_id' => $this->city_id,
            'user_id' => $this->user_id,
//            'min_price' => $this->min_price,
            'main_min_price' => $this->main_min_price,
            'rent_type' => $this->rent_type,

//            'min_price' => $this->when(isset($this->min_price), function () {
//                return $this->min_price;
//            }),
            'is_favorite' => $this->when(isset($this->is_favorite), function () {
                return $this->is_favorite;
            }),
            'images' => ProductImagesResource::collection($this->whenLoaded('images')),
        ];
    }
}
