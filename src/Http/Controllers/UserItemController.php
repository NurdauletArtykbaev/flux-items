<?php

namespace Nurdaulet\FluxItems\Http\Controllers;

use Nurdaulet\FluxItems\Http\Requests\Product\ProductStoreRequest;
use Nurdaulet\FluxItems\Http\Requests\Product\ProductUpdateRequest;
use Nurdaulet\FluxItems\Http\Resources\Product\ProductResource;
use Nurdaulet\FluxItems\Http\Resources\Product\TestProductsResource;
use Nurdaulet\FluxItems\Services\ItemService;
use Nurdaulet\FluxItems\Repositories\ItemRepository;
//use App\Services\v2\StoreEmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserItemController
{
    public function __construct(private ItemRepository       $itemRepository,
                                private ItemService          $itemService,
    )
    {
    }

    public function index($userId, Request $request)
    {
        $filters = $request->filters ?? [];
        $filters['user_id'] = $userId;
        $filters['newest'] = true;

        $items = $this->itemService->getPaginated($filters);
        return TestProductsResource::collection($items);
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        $user = $request->user();
        $lordId = $user->id;
        if (config('flux-items.use_roles')) {

//            $lordId = $this->storeEmployeeService->getLordId($user);
        }
        $this->itemService->update($id, ['user_id' => $lordId], $request->validated());
    }

    public function myItems(Request $request)
    {
        $user = $request->user();
//        $lordId = $this->storeEmployeeService->getLordId($user);
        $lordId = $user->id;
        $filters = [
            'newest' => 1,
            'user_id' => $lordId,
            'exists' => []
        ];
        $filters['status'] = $request->status ?? 'all';

        $activeItemsCount = $this->itemRepository->count(['user_id' => $lordId, 'status' => 'active']);
        $deactiveItemsCount = $this->itemRepository->count(['user_id' => $lordId, 'status' => 'deactive']);
        $items = $this->itemService->getPaginated($filters, null, null, null);
//        $items = $this->itemService->getPaginated($filters, null, null, ['orders' => function ($query) {
//            return $query->where('status', 5);
//        }]);
        $items->load('viewHistory', 'cities');

        return TestProductsResource::collection($items)
            ->additional([
                'items_count' => $activeItemsCount + $deactiveItemsCount,
                'active_items_count' => $activeItemsCount,
                'deactive_items_count' => $deactiveItemsCount,
            ]);
    }


    public function updateAdStatus(Request $request, $id)
    {
        $lordId = $request->user()->id;
        if (config('flux-items.options.use_role')) {
//            $lordId = $this->storeEmployeeService->getLordId($request->user());
        }
        $this->itemService->update($id, ['user_id' => $lordId], ['status' => $request->status]);

        return response()->noContent();
    }

    public function myItem(Request $request, $id)
    {
        $user = $request->user();
        return new ProductResource($this->itemService->findByUser($id, $user, []));
//        return new ProductResource($this->itemService->findByUser($id, $user, ['orders' => function ($query) {
//            return $query->where('status', 5);
//        }]));
    }


    public function store(ProductStoreRequest $request)
    {
        $this->itemService->store($request);

        return response()->noContent();
    }

    public function destroy($id, Request $request)
    {
        $this->itemService->delete($id, $request->user());
        return response()->noContent();
    }
}
