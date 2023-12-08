<?php

namespace Nurdaulet\FluxItems\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Nurdaulet\FluxItems\Http\Resources\ConditionsResource;
class ConditionController
{


    public function __invoke()
    {
        $lang = app()->getLocale();
        return Cache::remember("conditions-new-$lang", 269746, function () {
            $conditions = config('flux-items.models.condition')::select('id', 'name', 'description')->active()->get();
            return ConditionsResource::collection($conditions);
        });
    }
}
