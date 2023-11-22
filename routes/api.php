<?php

use Nurdaulet\FluxItems\Http\Controllers\CartController;
use Nurdaulet\FluxItems\Http\Controllers\ItemController;
use Nurdaulet\FluxItems\Http\Controllers\FavoriteController;
use Nurdaulet\FluxItems\Http\Controllers\UserItemController;
use Nurdaulet\FluxItems\Http\Controllers\SearchController;
use Nurdaulet\FluxItems\Http\Controllers\ItemViewHistoryController;
use Nurdaulet\FluxItems\Http\Controllers\ItemReviewController;
use Nurdaulet\FluxItems\Http\Controllers\ComplaintItemController;
use Nurdaulet\FluxItems\Http\Controllers\ProtectMethodController;
use Nurdaulet\FluxItems\Http\Controllers\ReceiveMethodController;
use Nurdaulet\FluxItems\Http\Controllers\ReturnMethodController;
use Nurdaulet\FluxItems\Http\Controllers\ConditionController;
use Nurdaulet\FluxItems\Http\Controllers\PromotionGroupController;
use Illuminate\Support\Facades\Route;


Route::prefix('api')->group(function () {
    Route::get('top-searches', [SearchController::class, 'topSearches']);
    Route::apiResource('promotion-groups', PromotionGroupController::class)->only(['index', 'show']);

    Route::get('/cart', [CartController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/cart', [CartController::class, 'update'])->middleware('auth:sanctum');
    Route::group(['prefix' => 'methods'], function () {
        Route::get('protect', ProtectMethodController::class);
        Route::get('receive', ReceiveMethodController::class);
        Route::get('return', ReturnMethodController::class);
    });
    Route::get('conditions', ConditionController::class);
    Route::prefix('items')->group(function () {
        Route::get('', [ItemController::class, 'index']);
        Route::get('hits', [ItemController::class, 'hits']);
        Route::get('new', [ItemController::class, 'newItems']);
//        Route::get('new/n8n', [ItemController::class, 'getNewAdsN8n']);
        Route::get('search', [SearchController::class, 'search']);
        Route::get('view-histories', [ItemViewHistoryController::class, 'index'])->middleware('auth:sanctum');
        Route::post('view-histories', [ItemViewHistoryController::class, 'store'])->middleware('auth:sanctum');
        Route::get('favorite', [FavoriteController::class, 'index'])->middleware('auth:sanctum');// +
        Route::post('favorite', [FavoriteController::class, 'syncFavorites'])->middleware('auth:sanctum');
        Route::get('{id}/similar', [ItemController::class, 'similarItems']);
        Route::get('{id}', [ItemController::class, 'show']);
        Route::get('{id}/reviews', [ItemReviewController::class, 'index'])->middleware('auth:sanctum');
        Route::post('{id}/reviews', [ItemReviewController::class, 'store'])->middleware('auth:sanctum');
        Route::post('{id}/complain', [ComplaintItemController::class, 'store']);
        Route::post('{id}/cart', [CartController::class, 'addToCart'])->middleware('auth:sanctum');
        Route::put('{id}/cart', [CartController::class, 'updateCartItem'])->middleware('auth:sanctum');
        Route::delete('{id}/cart', [CartController::class, 'removeFromCart'])->middleware('auth:sanctum');
    });

    Route::group(['prefix' => 'user','middleware' => 'auth:sanctum'], function () {
        Route::get('my-items', [UserItemController::class, 'myItems']);
        Route::get('my-items/{id}', [UserItemController::class, 'myItem']);
        Route::delete('my-items/{id}', [UserItemController::class, 'destroy']);
        Route::post('my-items', [UserItemController::class, 'store']);
        Route::post('my-items/{id}/status', [UserItemController::class, 'updateAdStatus']);
        Route::post('my-items/{id}', [UserItemController::class, 'update']);
    });

    Route::get('user/{id}/items', [UserItemController::class, 'index']);
});
