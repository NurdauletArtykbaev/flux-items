<?php
namespace Nurdaulet\FluxItems\Http\Resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'last_name' => $this->last_name,
            'company_name' => $this->company_name,
            'items' => $this->whenLoaded('items', function () {
                return CartItemResource::collection($this->items);
            }),
        ];
    }
}
