<?php

namespace Nurdaulet\FluxItems\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Nurdaulet\FluxItems\Http\Resources\CatalogResource;
use Illuminate\Support\Facades\Storage;

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
            'banner_catalog_id' => $this->banner_catalog_id,
            'banner_catalog' => $this->whenLoaded('catalog', function () {
                return new CatalogResource($this->catalog);
            }),
            'catalogs' => $this->whenLoaded('catalogs', function () {
                return CatalogResource::collection($this->catalogs);
            }),
            'selected_catalog_id' => $this->whenLoaded('catalogs', function () {
                return $this->catalogs->first()?->id;
            }),
            'banner_title' => $this->banner_title,
            'banner_position_left' =>(bool) $this->banner_position_left,
            'banner_bg_color' => $this->banner_bg_color,
            'banner_image' =>  $this->banner_image ? Storage::disk('s3')->url($this->banner_image) : null,
        ];
    }
}
