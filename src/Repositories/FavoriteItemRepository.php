<?php

namespace Nurdaulet\FluxItems\Repositories;

use Nurdaulet\FluxItems\Http\Resources\Product\TestProductsResource;
use Nurdaulet\FluxItems\Services\ItemService;

class FavoriteItemRepository
{

    public function __construct(private ItemService $itemService)
    {
    }

    public function getFavoriteItems($user)
    {
        $user = config('flux-items.models.user')::findOrFail($user->id);
        $favoriteProductIds = $user->favorites()
            ->whereNotNull('item_id')->get()
            ->pluck('item_id')->unique()
            ->toArray();
        if (!count($favoriteProductIds)) {
            return   collect();
        }
        return  $this->itemService->get(['ids' => $favoriteProductIds]);
    }

    public function create($user, $id)
    {
        return config('flux-items.models.favorite_item')::create([
            'user_id' => $user->id,
            'item_id' => $id
        ]);
    }

}
