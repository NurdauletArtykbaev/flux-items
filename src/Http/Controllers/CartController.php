<?php

namespace Nurdaulet\FluxItems\Http\Controllers;

use Illuminate\Http\Request;
use Nurdaulet\FluxItems\Http\Requests\Cart\AddToCartRequest;
use Nurdaulet\FluxItems\Http\Requests\Cart\CartItemSaveRequest;
use Nurdaulet\FluxItems\Http\Requests\Cart\CartSaveRequest;
use Nurdaulet\FluxItems\Http\Resources\Cart\CartResource;
use Nurdaulet\FluxItems\Services\CartService;

class CartController
{
    public function __construct(private CartService $cartService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $cart = $this->cartService->get($user);
        return new CartResource($cart);
    }

    public function update(CartSaveRequest $request)
    {
        $user = $request->user();
        $this->cartService->update($user, $request->validated());
        return response()->noContent();
    }

    public function updateCartItem($id, CartItemSaveRequest $request)
    {
        $user = $request->user();
        $this->cartService->updateCartItem($user, $id, $request->validated());
        return response()->noContent();
    }

    public function addToCart($id, AddToCartRequest $request): \Illuminate\Http\Response
    {
        $user = $request->user();
        $qty = $request->quantity ?? 1;
        $this->cartService->add($user, $id, $qty);
        return response()->noContent();
    }

    public function removeFromCart($id, Request $request)
    {
        $user = $request->user();
        $this->cartService->remove($user, $id);
        return response()->noContent();
    }
}
