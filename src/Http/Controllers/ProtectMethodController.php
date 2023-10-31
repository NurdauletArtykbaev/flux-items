<?php

namespace Nurdaulet\FluxItems\Http\Controllers;

use Nurdaulet\FluxItems\Http\Resources\ProtectMethodsResource;
use Illuminate\Support\Facades\Cache;

class ProtectMethodController
{
    public function __invoke()
    {
        $lang = app()->getLocale();
        return Cache::remember("protected-methods-$lang", config('flux-items.options.cache_expiration'), function () {
            $protectMethods = config('flux-items.models.protect_method')::active()->get();
            return ProtectMethodsResource::collection($protectMethods);
        });

    }
}
