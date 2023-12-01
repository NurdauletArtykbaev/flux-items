<?php

namespace Nurdaulet\FluxItems\Http\Resources\Search;

use Nurdaulet\FluxCatalog\Http\Resources\CatalogsResource;
use Nurdaulet\FluxItems\Http\Resources\Product\ProductImagesResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'is_hit' => $this->is_hit,
//            'is_busy' => $this->is_busy,
            'user_id' => $this->user_id,
            'catalog' => $this->whenLoaded('catalogs', function () {
                return new CatalogsResource($this->catalogs->first());
            }),
            'is_favorite' => $this->when(isset($this->is_favorite), function () {
                return $this->is_favorite;
            }),
            'images' => ProductImagesResource::collection($this->whenLoaded('images')),
        ];
    }
}
