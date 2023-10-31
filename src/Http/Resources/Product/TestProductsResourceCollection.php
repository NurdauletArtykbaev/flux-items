<?php

namespace Nurdaulet\FluxItems\Http\Resources\Product;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TestProductsResourceCollection extends ResourceCollection
{
    public $collects = TestProductsResource::class;

    public mixed $categoriesSummary;

    public function __construct($resource, $categoriesSummary)
    {
        parent::__construct($resource);
        $this->categoriesSummary = $categoriesSummary;
    }

    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'categories' => $this->categoriesSummary,
        ];
    }
}
