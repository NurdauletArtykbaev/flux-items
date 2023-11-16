<?php

namespace Nurdaulet\FluxItems\Http\Controllers;


use Nurdaulet\FluxItems\Helpers\ItemHelper;
use Nurdaulet\FluxItems\Http\Resources\PromotionGroupsResource;
use Illuminate\Http\Request;

class PromotionGroupController
{
    public function index(Request $request)
    {
        return PromotionGroupsResource::collection(config('flux-items.models.promotion_group')::active()->get());
    }

    public function show($id, Request $request)
    {
        return new PromotionGroupsResource(config('flux-items.models.promotion_group')::with(["catalogs.items" => fn($query) => $query->with(['images', 'cities', ItemHelper::getPriceRelation()]), 'catalog'])
            ->active()->findOrFail($id));
    }
}
