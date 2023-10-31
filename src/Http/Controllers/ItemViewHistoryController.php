<?php

namespace Nurdaulet\FluxItems\Http\Controllers;

use Nurdaulet\FluxItems\Http\Requests\Product\SaveItemViewRequest;
use Nurdaulet\FluxItems\Http\Resources\Product\TestProductsResource;
use Nurdaulet\FluxItems\Services\ItemService;

class ItemViewHistoryController 
{
    public function __construct(private ItemService $itemService)
    {
    }

    public function index()
    {
        $itemIds = config('flux-items.models.view_history_item')::where('user_id', auth()->user()->id)
            ->latest('id')
            ->limit(10)
            ->get()
            ->pluck('item_id')->unique()->toArray();
        return TestProductsResource::collection($this->itemService->getNewDataProducts(['ids' => $itemIds,'limit' => 8]));
    }

    public function store(SaveItemViewRequest $request)
    {
        $ids = $request->ids;
        if (count($ids) > 1) {
            foreach ($ids as $id) {
                config('flux-items.models.view_history_item')::create([
                    'user_id' => auth()->user()->id,
                    'item_id' => $id,
                    'platform' => $request->header('platform'),
                    'ip' => $request->ip(),
                ]);
            }
        } else {
            $item = $this->itemService->find(array_shift($ids));
            $item->viewHistory()->firstOrCreate();
            $item->viewHistory()->increment('count');
            config('flux-items.models.view_history_item')::create([
                'user_id' => auth()->user()->id,
                'item_id' => $item->id,
                'platform' => $request->header('platform'),
                'ip' => $request->ip(),
            ]);
        }
    }
}
