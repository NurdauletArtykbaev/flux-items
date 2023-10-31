<?php

namespace Nurdaulet\FluxItems\Http\Controllers;

use Nurdaulet\FluxItems\Http\Resources\ReceiveMethodsResource;
use Illuminate\Support\Facades\Cache;

class ReceiveMethodController
{
    public function __invoke()
    {
        $lang = app()->getLocale();
        return Cache::remember("receive-methods-new-$lang", config('flux-items.options.cache_expiration'), function () {
            return ReceiveMethodsResource::collection(config('flux-items.models.receive_method')::active()->get());
        });
    }
}
