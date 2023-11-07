<?php

namespace Nurdaulet\FluxItems\Http\Controllers;


use Nurdaulet\FluxItems\Http\Resources\PromotionGroupsResource;
use Nurdaulet\FluxItems\Models\PromotionGroup;
use Illuminate\Http\Request;

class PromotionGroupController
{
    public function index(Request $request)
    {
        return PromotionGroupsResource::collection(config('flux-items.models.promotion_group')::active()->get());
    }
    public function show($id, Request $request)
    {
        return PromotionGroupsResource::collection(config('flux-items.models.promotion_group')::with(['catalogs.items','catalog'])->active()->findOrFail($id));
    }
}
