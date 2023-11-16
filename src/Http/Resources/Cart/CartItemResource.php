<?php
namespace Nurdaulet\FluxItems\Http\Resources\Cart;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Nurdaulet\FluxItems\Http\Resources\Product\TestProductsResource;
use Nurdaulet\FluxItems\Http\Resources\ReceiveMethodsResource;
use Nurdaulet\FluxItems\Http\Resources\UserAddressResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = (new TestProductsResource($this))->toArray($request);
        $data['quantity'] =(int) $this->pivot->quantity;
        $data['receive_method'] =  new ReceiveMethodsResource($this->pivot->receiveMethod);
        $data['user_address'] =  new UserAddressResource($this->pivot->userAddress);
        return $data;
    }
}
