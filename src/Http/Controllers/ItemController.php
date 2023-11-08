<?php

namespace Nurdaulet\FluxItems\Http\Controllers;

use Nurdaulet\FluxItems\Http\Requests\Product\SimilarProductRequest;
use Nurdaulet\FluxItems\Http\Resources\Product\ProductResource;
use Nurdaulet\FluxItems\Http\Resources\Product\TestProductsResource;
use Nurdaulet\FluxItems\Services\ItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ItemController
{

    public function __construct(private ItemService $itemService)
    {
    }

    public function similarItems($itemId, SimilarProductRequest $request)
    {
        $items = $this->itemService->getSimilarItems($itemId, $request->validated());
        return TestProductsResource::collection($items);
    }


    public function index(Request $request)
    {
        $filters = $request->input('filters', []);
        $filters['city_id'] = $request->header('city_id');

        $ads = $this->itemService->getPaginationTestProducts($filters);
        $maxPrice = isset($filters['with_max_price']) ? $this->itemService->getMaxPrice($filters) : 0;

        return TestProductsResource::collection($ads)->additional(['max_price' => $maxPrice]);
    }


    public function show($id)
    {
        return new ProductResource($this->itemService->show($id));
    }

    public function hits(Request $request)
    {
        $filters = $request->get('filters');
        $filters['city_id'] = $request->header('city_id');
        unset($filters['newest']);
        return Cache::remember("hits_items-new-" . json_encode($filters), 3600, function () use ($filters) {
            $hitFilers = [
                'limit' => 8,
                'in_random' => true,
                'is_hit' => true,
            ];
            $filters = array_merge($filters, $hitFilers);
            return TestProductsResource::collection($this->itemService->getNewDataProducts($filters));
        });
    }

    public function newItems(Request $request)
    {
        $filters = $request->get('filters');
        $filters['city_id'] = $request->header('city_id');
        return Cache::remember("new-items-" . json_encode($filters), 172800, function () use ($filters) {
            $hitFilers = [
                'limit' => 24,
                'newest' => true,
            ];
            $filters = array_merge($filters, $hitFilers);
            return TestProductsResource::collection($this->itemService->getNewDataProducts($filters));
        });
    }

    public function getNewAdsN8n()
    {
        $filters = [
            'n8n' => true,
        ];
        $data = $this->itemService->getProducts($filters);

        $rentTypes = Cache::remember("rent-types-n8n", 269746, function () {
            return config('flux-items.models.rent_type')::all();
        });
        if (!empty($data->toArray())) {
            $data['url'] = config('flux-items.options.site_items_base_url') . $data->slug;
            $data['rent_type_name'] = $rentTypes->where('slug', $data->rent_type)->first()?->name ?? $data->rent_type;
        }

        return response()->json(['data' => $data]);
    }
}
