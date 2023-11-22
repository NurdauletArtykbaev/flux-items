<?php

namespace Nurdaulet\FluxItems\Http\Resources\Product;

use Nurdaulet\FluxItems\Http\Resources\CitiesResource;
use Nurdaulet\FluxCatalog\Http\Resources\CatalogsResource;
use Nurdaulet\FluxItems\Helpers\ItemHelper;
use Nurdaulet\FluxItems\Http\Resources\ConditionsResource;
use Nurdaulet\FluxItems\Http\Resources\ProtectMethodsResource;
use Nurdaulet\FluxItems\Http\Resources\ReceiveMethodsResource;
use Nurdaulet\FluxItems\Http\Resources\ReturnMethodsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $itemType = ($this->type ?? ItemHelper::TYPE_RENT);
        $isItemTypeRent = $itemType == ItemHelper::TYPE_RENT;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'is_hit' => $this->is_hit,
            'is_favorite' => $this->is_favorite,
            'is_required_confirm' => $this->is_required_confirm,
            'is_busy' => $this->is_busy,
            'description' => $this->description,
            'orders_count' => $this->whenCounted('orders'),
            'reviews_count' => $this->reviews_count,
            'catalogs' => CatalogsResource::collection($this->whenLoaded('catalogs')),
            'view_history' => new ProductViewHistoryResource($this->whenLoaded('viewHistory')),
            'return_methods' => ReturnMethodsResource::collection($this->whenLoaded('returnMethods')),
            'receive_methods' => ReceiveMethodsResource::collection($this->whenLoaded('receiveMethods')),
            'protect_methods' => ProtectMethodsResource::collection($this->whenLoaded('protectMethods')),
            'images' => ProductImagesResource::collection($this->whenLoaded('images')),
            'user' => new ProductUserResource($this->whenLoaded('user')),
            'condition' => new ConditionsResource($this->whenLoaded('condition')),
            'rent_types' => $this->when($isItemTypeRent, function () {
                if (config('flux-items.options.is_rent_daily')) {
                    return ProductRentTypesIsDailyResource::collection($this->whenLoaded('rentTypes'));
                }
                return ProductRentTypesResource::collection($this->whenLoaded('rentTypes'));
            }),
            'price' => $this->when(!$isItemTypeRent, fn() => (int) $this->price),
            'old_price' => $this->when(!$isItemTypeRent, fn() => (int) $this->old_price),
            'cities' => CitiesResource::collection($this->whenLoaded('cities')),
            'type_raw' => $itemType,
            'created_at' => $this?->created_at->format('d.m.Y'),
            'type' => ItemHelper::TYPES[$itemType],
        ];
    }
}
