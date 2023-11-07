<?php

namespace Nurdaulet\FluxItems\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Nurdaulet\FluxItems\Http\Resources\CatalogResource;

class PromotionGroupsResource extends JsonResource
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
            'banner_catalog' => $this->whenLoaded('catalog', function () {
                return new CatalogResource($this->catalog);
            }),
            'catalogs' => $this->whenLoaded('catalogs', function () {
                return  CatalogResource::collection($this->catalogs);
            }),
            'banner_title' => $this->banner_title,
            'banner_position_left' => $this->banner_position_left,
            'banner_bg_color' => $this->banner_bg_color,
            'banner_image' => $this->banner_image,
            'reorder' => $this->reorder
        ];
    }
}
