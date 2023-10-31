<?php

namespace Nurdaulet\FluxItems\Http\Controllers;

use Nurdaulet\FluxItems\Http\Requests\Product\SaveFavoriteItemsRequest;
use Nurdaulet\FluxItems\Http\Resources\Product\TestProductsResource;
use Nurdaulet\FluxItems\Services\FavoriteItemService;
use Illuminate\Http\Request;

class FavoriteController 
{
    public function __construct(private FavoriteItemService $userFavoriteService)
    {
    }

    public function syncFavorites(SaveFavoriteItemsRequest $request)
    {
        $user = $request->user();

        foreach ($request->input('ids') as $id) {
            $this->userFavoriteService->syncFavoriteItem($user, $id);
        }

        return response()->noContent();
    }


    public function index(Request $request)
    {
        $items = $this->userFavoriteService->getFavoriteItems($request->user());
        return TestProductsResource::collection($items);
    }
}
