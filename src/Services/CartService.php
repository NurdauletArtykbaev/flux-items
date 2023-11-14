<?php

namespace Nurdaulet\FluxItems\Services;

use Nurdaulet\FluxItems\Helpers\ItemHelper;

class CartService
{

    public function get($user)
    {
        $cartItems = config('flux-items.models.cart')::where('user_id', $user->id)
            ->with(['item' => function($query) {
                return $query->with(ItemHelper::getPriceRelation());
            }])

            ->get();
        $users =  config('flux-items.models.user')::whereIn('id',$cartItems->pluck('item.user_id')->flatten()->toArray())->get();

        foreach ($users as $user) {
            $items = $cartItems->where('item.user_id', $user->id);
            $user->setRelation('items', $items->pluck('item')->flatten());
        }
        return [ $users, collect()];
    }

    public function add($user,$itemId, $qty)
    {
        config('flux-items.models.cart')::updateOrCreate([
            'user_id' => $user->id,
            'item_id' => $itemId
        ],
            [
                'user_id' => $user->id,
                'item_id' => $itemId,
                'quantity' => $qty
            ]);
    }

    public function remove( $user, $itemId)
    {
        config('flux-items.models.cart')::where('user_id', $user->id)->where('item_id', $itemId)->delete();
    }
}
