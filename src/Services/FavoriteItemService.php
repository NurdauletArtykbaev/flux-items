<?php

namespace Nurdaulet\FluxItems\Services;

use Nurdaulet\FluxItems\Repositories\ItemRepository;
use Nurdaulet\FluxItems\Repositories\FavoriteItemRepository;

class FavoriteItemService
{

    public function __construct(
        private FavoriteItemRepository $favoriteItemRepository,
        private ItemRepository      $itemRepository,
    )
    {
    }

    public function getFavoriteItems($user)
    {
        return $this->favoriteItemRepository->getFavoriteProducts($user);
    }

    public function syncFavoriteItem($user, $id)
    {
        $item = $this->itemRepository->find($id);
        $item->viewHistory()->firstOrCreate();
        if (!$item->is_favorite) {
            $this->favoriteItemRepository->create($user, $id);
            $item->viewHistory()->increment('favorite_count');
            return true;
        }

        $item->viewHistory()->where('favorite_count', '>', 0)->decrement('favorite_count');

        config('flux-items.models.favorite_item')::where('item_id', $id)
            ->where('user_id', $user->id)
            ->delete();
        return true;
    }

}
