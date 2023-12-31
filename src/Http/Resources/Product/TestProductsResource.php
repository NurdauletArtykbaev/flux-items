<?php

namespace Nurdaulet\FluxItems\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use Nurdaulet\FluxItems\Helpers\ItemHelper;
use Nurdaulet\FluxItems\Http\Resources\CitiesResource;
use Nurdaulet\FluxItems\Http\Resources\UserAddressResource;

class TestProductsResource extends JsonResource
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
        [$price, $oldPrice] = $this->getPrice($itemType);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
//            'is_busy' => $this->is_busy,
            'is_required_confirm' => $this->is_required_confirm,
            'user_id' => $this->user_id,
            'price' => (int)$price,
            'properties' => $this->whenLoaded('properties', function () {
                $this->properties->loadMissing('pivot.value');
                return ProductPropertiesResource::collection($this->properties);
            }),
            'old_price' => (int)$oldPrice,
            'rent_type' => $this->when($itemType == ItemHelper::TYPE_RENT, function () {
                $rentType = $this->rentTypes->first();
                return [
                    'id' => $rentType?->id,
                    'name' => $rentType?->name,
                    'slug' => $rentType?->slug
                ];
            }),
            'is_favorite' => $this->when(isset($this->is_favorite), function () {
                return $this->is_favorite;
            }),
            'status_raw' => $this->whenHas('status', function () {
                return $this->status;
            }),
            'status' => $this->whenHas('status', function () {
                return ItemHelper::STATUSES[$this->status] ?? ItemHelper::STATUS_AVAILABLE;
            }),
            'is_active' => $this->is_active,
            'images' => ProductImagesResource::collection($this->whenLoaded('images')),
            'address' => $this->whenLoaded('userAddress', function () {
                return new UserAddressResource($this->userAddress);
            }),
            'delivery_address' => $this->whenLoaded('pivot.userAddress', function () {
                return new UserAddressResource($this->pivot?->userAddress);
            }),
            'user' => new ProductsUserResource($this->whenLoaded('user')),
            'view_history' => $this->when($this->whenHas('viewHistory'), function () {
                return $this->viewHistory ? new ProductViewHistoryResource($this->viewHistory) : [
                    'count' => 0,
                    'view_phone_count' => 0,
                    'favorite_count' => 0,
                ];
            }),
//            'view_history' => new ProductViewHistoryResource($this->whenLoaded('viewHistory')),
            'city' => $this->whenLoaded('cities', function () {
                return new CitiesResource($this->cities?->first());
            }),
            'type_raw' => $itemType,
            'type' => ItemHelper::TYPES[$itemType],
        ];
    }

    private function getPrice($itemType)
    {
        $rentType = $this->rentTypes->first();

        if ($itemType == ItemHelper::TYPE_SELL) {
            $price = $this->price;
            $oldPrice = $this->old_price;
        } else if (config('flux-items.options.is_rent_daily')) {
            $price = $rentType?->pivot?->prices?->first()?->price;
            $oldPrice = $rentType?->pivot?->prices?->first()?->price;
        } else {
            $price = $rentType?->pivot?->price;
            $oldPrice = $rentType?->pivot?->old_price;
        }
        return [$price, $oldPrice];
    }
}
