<?php

namespace Nurdaulet\FluxItems\Repositories;

use Nurdaulet\FluxItems\Filters\ItemFilter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Nurdaulet\FluxItems\Helpers\ItemHelper;

class ItemRepository
{
    public function __construct()
    {
    }

    public function get($filters = [], $relations = ['images', 'cities'], $exists = ['images'])
//    public function get($filters = [], $relations = ['images', 'user'], $exists = ['images'])
    {
        $user = auth()->guard('sanctum')->user();
        $filters['exists'] = $exists;
        $relations[] = ItemHelper::getPriceRelation();

        DB::statement('SET session sql_log_bin = 0;');
        $query = config('flux-items.models.item')::whereHas('rentTypes')
            ->select('items.id', 'items.name', 'items.slug',
                'items.is_hit', 'items.is_busy', 'items.user_id',
                'items.type', 'items.price', 'items.old_price',
            );

        $query = $query
            ->with($relations)
            ->applyFilters(new ItemFilter(), $filters)
            ->withExists(['favorites as is_favorite' => function ($query) use ($user) {
                return $query->where('user_id', $user?->id);
            }]);

        DB::statement('SET session sql_log_bin = 1;');
        return $query->get();
    }


    public function getPaginated($filters = [], $relations = ['images', 'cities'],
                                              $exists = ['images'], $withCount = [])
//    public function getPaginated($filters = [], $relations = ['images', 'user'],
//                                              $exists = ['images'], $withCount = [])
    {

        $query = config('flux-items.models.item')::select('items.id', 'items.name', 'items.slug',
            'items.is_hit', 'items.is_busy', 'items.user_id',
            'items.type', 'items.price', 'items.old_price',
        );


        if (is_null($relations)) {
            $relations = ['images'];
        }
        $filters['exists'] = $exists;

        $user = auth()->guard('sanctum')->user();

        $query = $query
            ->with($relations)
            ->applyFilters(new ItemFilter(), $filters)
            ->when($withCount && count($withCount), fn($query) => $query->withCount($withCount))
            ->withExists(['favorites as is_favorite' => function ($query) use ($user) {
                return $query->where('user_id', $user?->id);
            }]);


        DB::statement('SET session sql_log_bin = 0;');

        $products = $query->paginate($filters['per_page'] ?? 20)
            ->appends(\request()->except('page'));

        DB::statement('SET session sql_log_bin = 1;');

        return $products;
    }

    public function getMaxPrice($filters = []): float
    {
        if (!isset($filters['status'])) {
            $filters['status'] = 'active';
        }

        unset($filters['lowest_price']);
        unset($filters['highest_price']);
        unset($filters['price_to']);
        unset($filters['price_from']);

        DB::statement('SET session sql_log_bin = 0;');

        $sub_query = config('flux-items.models.item')::query()
            ->applyFilters(new ItemFilter(), $filters);

        if (!$this->joined($sub_query, 'rent_item_prices')) {
            $sub_query = $sub_query->join('rent_item_prices', 'items.id', '=', 'rent_item_prices.item_id');
        }

        $sub_query = $sub_query->toBase();

        $minPriceSubQuery = $sub_query
            ->selectRaw('MIN(rent_item_prices.price) as min_price, rent_item_prices.item_id')
            ->groupBy('rent_item_prices.item_id');

        $maxPrice = DB::table(DB::raw("({$minPriceSubQuery->toSql()}) as sub"))
            ->mergeBindings($minPriceSubQuery)
            ->max('min_price');

        DB::statement('SET session sql_log_bin = 1;');

        return round(ceil($maxPrice / 500) * 500);
    }

    public function create($data)
    {
        return config('flux-items.models.item')::create($data);
    }

    public function count($filters = [])
    {
        return config('flux-items.models.item')::query()
            ->applyFilters(new ItemFilter(), $filters)->count();
    }

    public function delete($id, $userId)
    {
        config('flux-items.models.item')::where('user_id', $userId)
            ->where('id', $id)
            ->delete();
        return true;
    }


    public function find($id, $relations = [], $filters = [], $withCount = [])
    {

        $user = auth()->guard('sanctum')->user();
        DB::statement('SET session sql_log_bin = 0;');
        $item = config('flux-items.models.item')::with($relations)->applyFilters(new ItemFilter(), $filters)
            ->withExists(['favorites as is_favorite' => function ($query) use ($user) {
                return $query->where('user_id', $user?->id ?? 0);
            }])
            ->withCount($withCount)
            ->whereNotNull('user_id')
            ->findOrFail($id);

        DB::statement('SET session sql_log_bin = 1;');

        return $item;
    }

    public function joined($query, $table)
    {
        $joins = $query->getQuery()->joins;
        if ($joins == null) {
            return false;
        }
        foreach ($joins as $join) {
            if ($join->table == $table) {
                return true;
            }
        }
        return false;
    }
}
