<?php

namespace Nurdaulet\FluxItems\Http\Controllers;

use Nurdaulet\FluxItems\Services\ItemService;
use Nurdaulet\FluxItems\Services\AlgoliaService;
use Illuminate\Http\Request;

class SearchController 
{

    public function __construct(private ItemService $itemService, private AlgoliaService $algoliaService)
    {
    }

    public function search(Request $request)
    {
        $q = $request->q ?? null;
        $cityId = $request->header('city_id');
        $data = $this->itemService->search($q, $cityId);

        return response()->json([
            'data' => $data
        ]);
    }

    public function topSearches()
    {
       $words = $this->algoliaService->topSearches();
       return response()->json(['data' => $words]);
    }
}
