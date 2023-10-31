<?php

namespace Nurdaulet\FluxItems\Services;

use Nurdaulet\FluxCatalog\Http\Resources\CatalogsResource;
use Nurdaulet\FluxItems\Http\Resources\Search\ProductsResource;
use Nurdaulet\FluxItems\Repositories\ItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Nurdaulet\FluxItems\Filters\ItemFilter;
use Nurdaulet\FluxItems\Services\StoreEmployeeService;

class ItemFacadeService
{

 

    public function countByUserId($userId)
    {
        return config('flux-items.models.item')::applyFilters(new ItemFilter(), ['user_id' => $userId])->count();


    }

}
