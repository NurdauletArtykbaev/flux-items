<?php

namespace Nurdaulet\FluxItems\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Nurdaulet\FluxItems\Http\Resources\Product\ProductsUserResource;
use Nurdaulet\FluxItems\Http\Resources\Product\ProductUserResource;
use Nurdaulet\FluxItems\Http\Resources\Product\TestProductsResource;
use Nurdaulet\FluxOrders\Http\Resources\PaymentMethodsResource;

class PaymentMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->imageUrl,
        ];
    }
}
