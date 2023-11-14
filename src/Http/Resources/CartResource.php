<?php

namespace Nurdaulet\FluxItems\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'users' => $this->resource['users'],
            'not_available_items' => $this->resource['not_available_items']
        ];
    }
}
