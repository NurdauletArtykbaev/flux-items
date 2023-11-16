<?php

namespace Nurdaulet\FluxItems\Http\Resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Nurdaulet\FluxItems\Http\Resources\PaymentMethodResource;
use Nurdaulet\FluxItems\Http\Resources\Product\ProductsUserResource;
use Nurdaulet\FluxItems\Http\Resources\Product\TestProductsResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'full_name' => $this->full_name,
            'payment_method' => $this->whenLoaded('paymentMethod', function () {
                return new PaymentMethodResource($this->paymentMethod);
            }),
            'phone' => $this->phone,
            'users' => CartUserResource::collection($this->users ?? collect()),
            'not_available_items' => TestProductsResource::collection($this->notAvailableItems  ?? collect())

//            'full_name' => $this->full_name,
//            'payment_method' => $this->whenLoaded('paymentMethod', function () {
//                return new PaymentMethodResource($this->paymentMethod);
//            }),
//            'phone' => $this->phone,
//            'users' => ProductsUserResource::collection($this->users ?? collect()),
//            'not_available_items' => TestProductsResource::collection($this->notAvailableItems  ?? collect())
        ];
    }
}
