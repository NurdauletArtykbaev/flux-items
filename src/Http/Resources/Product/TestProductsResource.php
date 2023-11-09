<?php

namespace Nurdaulet\FluxItems\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use Nurdaulet\FluxItems\Helpers\ItemHelper;
use Nurdaulet\FluxItems\Http\Resources\CitiesResource;

class TestProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $rentType = $this->rentTypes->first();
        if (config('flux-items.options.is_rent_daily')) {
            $mainMinPrice = $rentType?->pivot?->prices?->first()?->price;
            $oldMinPrice = $rentType?->pivot?->prices?->first()?->price;
        } else {
            $mainMinPrice = $rentType?->pivot?->price;
            $oldMinPrice = $rentType?->pivot?->old_price;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'is_busy' => $this->is_busy,
            'user_id' => $this->user_id,
            'main_min_price' => $mainMinPrice,
            'old_min_price' => $oldMinPrice,
            'rent_type' => [
                'name' => $rentType?->name,
                'slug' => $rentType?->slug
            ],
            'is_favorite' => $this->when(isset($this->is_favorite), function () {
                return $this->is_favorite;
            }),
            'images' => ProductImagesResource::collection($this->whenLoaded('images')),
            'user' => new ProductsUserResource($this->whenLoaded('user')),
            'view_history' => new ProductViewHistoryResource($this->whenLoaded('viewHistory')),
            'city' => $this->whenLoaded('cities', function () {
                return new CitiesResource($this->cities?->first());
            }),
            'type_raw' => $this->type ?? ItemHelper::TYPE_RENT,


            'type' => ItemHelper::TYPES[$this->type] ??  ItemHelper::TYPES[ItemHelper::TYPE_RENT],
        ];
    }
}
