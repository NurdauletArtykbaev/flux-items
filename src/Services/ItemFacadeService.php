<?php

namespace Nurdaulet\FluxItems\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Nurdaulet\FluxItems\Filters\ItemFilter;
use Nurdaulet\FluxItems\Helpers\ItemHelper;

class ItemFacadeService
{

    public function getType($type)
    {
        return ItemHelper::getTypes()[$type];
    }

    public function countByUserId($userId)
    {
        return config('flux-items.models.item')::applyFilters(new ItemFilter(), ['user_id' => $userId])->count();
    }

}
