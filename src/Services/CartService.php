<?php

namespace Nurdaulet\FluxItems\Services;

use Nurdaulet\FluxItems\Helpers\ItemHelper;
use Nurdaulet\FluxItems\Models\Cart;

class CartService
{
    public function update($user, $data)
    {

        config('flux-items.models.cart')::where('user_id', $user->id)
            ->update($data);
    }

    public function updateCartItem($user, $id, $data)
    {
        $cart = config('flux-items.models.cart')::where('user_id', $user->id)->firstOrCreate();
        if ($cart?->id) {
            config('flux-items.models.cart_item')::where('cart_id', $cart->id)->where('item_id', $id)->update($data);
        }

    }

    public function get($user)
    {
        $cart = config('flux-items.models.cart')::where('user_id', $user->id)
            ->with(['paymentMethod', 'items' => function ($query) {
                return $query->with([ItemHelper::getPriceRelation(), 'images', 'pivot.receiveMethod', 'pivot.userAddress']);
            }])
            ->first();
        if (!$cart?->id) {
            return new Cart();
        }
        $users = config('flux-items.models.user')::whereIn('id', $cart->items->pluck('user_id')->toArray())->get();

        foreach ($users as $user) {
            $items = $cart->items->where('user_id', $user->id);
            $user->setRelation('items', $items);
        }
        $cart->setRelation('users', $users);
        $cart->setRelation('notAvailableItems', collect());
        return $cart;
    }

    public function add($user, $itemId, $data)
    {
        $cart = config('flux-items.models.cart')::firstOrCreate(['user_id' => $user->id]);
        config('flux-items.models.cart_item')::updateOrCreate([
            'cart_id' => $cart->id,
            'item_id' => $itemId
        ],
            $data
        );
    }

    public function remove($user, $itemId)
    {
        $cart = config('flux-items.models.cart')::where('user_id', $user->id)->first();
        if ($cart?->id) {
            config('flux-items.models.cart_item')::where('cart_id', $cart->id)
                ->where('item_id', $itemId)
                ->delete();
            if (!$cart->items()->exists()) {
                $cart->delete();
            }
        }
    }
}
