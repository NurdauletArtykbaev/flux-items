<?php

namespace Nurdaulet\FluxItems\Http\Controllers;

use Nurdaulet\FluxItems\Http\Resources\ReturnMethodsResource;
use Illuminate\Support\Facades\Cache;

class ReturnMethodController
{
    public function __invoke()
    {
        $lang = app()->getLocale();
        return Cache::remember("return-methods-new-$lang", config('flux-items.options.cache_expiration'), function () {
            return ReturnMethodsResource::collection(config('flux-items.models.receive_method')::active()->get());
        });
    }
}
