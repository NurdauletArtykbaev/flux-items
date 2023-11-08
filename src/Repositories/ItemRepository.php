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

    public function getProducts($filters = [], $relations = ['images', 'user'], $exists = ['images'])
    {
        $user = auth()->guard('sanctum')->user();
        $filters['exists'] = $exists;

        DB::statement('SET session sql_log_bin = 0;');
        $query = config('flux-items.models.item')::whereHas('rentTypes')
            ->select('items.id', 'items.name', 'items.slug', 'items.is_hit', 'items.is_busy', 'items.user_id');

        $query = $query
            ->with($relations)
            ->applyFilters(new ItemFilter(), $filters)
            ->withExists(['favorites as is_favorite' => function ($query) use ($user) {
                return $query->where('user_id', $user?->id);
            }]);

        $query = $this->addToQueryMinMainPriceAndType($query);

//        if (isset($filters['n8n'])) {
//            $query = $query->where('items.got_n8n', false)->orderBy('items.id', 'DESC');
////            $query = $query->where('items.got_n8n', false)->orderBy('items.id');
//            $item = $query->addSelect('items.city_id')->with('city')->first();
//            if ($item) {
//                config('flux-items.models.item')::where('id', $item->id)->update([
//                    'got_n8n' => true
//                ]);
//                return $item;
//            }
//        }

        DB::statement('SET session sql_log_bin = 1;');
        return $query->get();
    }

    public function getNewDataProducts($filters = [], $relations = ['images', 'user'], $exists = ['images'])
    {
        $user = auth()->guard('sanctum')->user();
        $filters['exists'] = $exists;
        $relations[] = ItemHelper::getPriceRelation();

        DB::statement('SET session sql_log_bin = 0;');
        $query = config('flux-items.models.item')::whereHas('rentTypes')
            ->select('items.id', 'items.name', 'items.slug', 'items.is_hit', 'items.is_busy', 'items.user_id');

        $query = $query
            ->with($relations)
            ->applyFilters(new ItemFilter(), $filters)
            ->withExists(['favorites as is_favorite' => function ($query) use ($user) {
                return $query->where('user_id', $user?->id);
            }]);


//        if (isset($filters['n8n'])) {
//            $query = $query->where('items.got_n8n', false)->orderBy('items.id', 'DESC');
////            $query = $query->where('items.got_n8n', false)->orderBy('items.id');
//            $item = $query->addSelect('items.city_id')->with('city')->first();
//            if ($item) {
//                config('flux-items.models.item')::where('id', $item->id)->update([
//                    'got_n8n' => true
//                ]);
//                return $item;
//            }
//        }

        DB::statement('SET session sql_log_bin = 1;');
        $products = $query->get();


        return $products;
    }

//    public function getPaginationProducts($filters = [], $relations = ['images', 'user'], $exists = ['images'], $withCount = [])
//    {
//
//        $query = config('flux-items.models.item')::select('items.id', 'items.name', 'items.slug', 'items.is_hit', 'items.is_busy', 'items.user_id')//        ->when(!is_null($relations), fn($query) =>$query->whereHas('rentTypes'))
//        ;
//
//
//        if (is_null($relations)) {
//            $relations = ['images'];
//        }
//        $filters['exists'] = $exists;
//
//        $user = auth()->guard('sanctum')->user();
//
//        $query = $query
//            ->with($relations)
//            ->whereHas('rentTypes')
//            ->applyFilters(new ItemFilter(), $filters)
//            ->when(count($withCount), fn($query) => $query->withCount($withCount))
//            ->withExists(['favorites as is_favorite' => function ($query) use ($user) {
//                return $query->where('user_id', $user?->id);
//            }]);
//
//
//        $query = $this->addToQueryMinMainPriceAndType($query);
//        DB::statement('SET session sql_log_bin = 0;');
//        $products = $query->paginate($filters['per_page'] ?? 20)
//            ->appends(\request()->except('page'));
//        DB::statement('SET session sql_log_bin = 1;');
//
//        return $products;
//    }

    public function getPaginationTestProducts($filters = [], $relations = ['images', 'user'],
                                              $exists = ['images'], $withCount = [])
    {

        $query = config('flux-items.models.item')::select('items.id', 'items.name', 'items.slug', 'items.is_hit', 'items.is_busy', 'items.user_id');


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

//    public function index($city_id = null, $filters = [])
//    {
//
//        $filters['city_id'] = $city_id;
//        return $this->get($filters ?? []);
//    }

//    public function paginate($city_id = null, $filters = [], $exists = [], $withCount = []): array
//    {
//        $filters['city_id'] = $city_id;
//        return $this->getAdsNew($filters ?? [], $exists, $withCount);
//    }

    public function get($filters = [], $relations = ['images', 'rentTypes.pivot.prices', 'user'])
    {
        if (!isset($filters['status'])) {
            $filters['status'] = 'active';
        }
        $user = auth()->guard('sanctum')->user();
        DB::statement('SET session sql_log_bin = 0;');
        $query = config('flux-items.models.item')::query()
            ->applyFilters(new ItemFilter(), $filters);
        $items = $query->selectRaw('items.id, items.name,items.is_required_deposit,items.is_busy,  items.is_hit, items.slug, items.user_id, items.store_id')
            ->withExists(['favorites as is_favorite' => function ($query) use ($user) {
                return $query->where('user_id', $user?->id);
            }])
            ->has('images')
            ->with($relations)->groupBy('items.id')
            ->get();
        DB::statement('SET session sql_log_bin = 1;');

        return $items;
    }

    public function getIds($filters = [])
    {
        if (!isset($filters['status'])) {
            $filters['status'] = 'active';
        }
        DB::statement('SET session sql_log_bin = 0;');
        $itemIds = config('flux-items.models.item')::query()
            ->applyFilters(new ItemFilter(), $filters)
            ->selectRaw('items.id')
            ->groupBy('items.id')
            ->get()->pluck('id')->toArray();
        DB::statement('SET session sql_log_bin = 1;');
        return $itemIds;
    }

//    public function getAdsNew($filters = [], $exists = ['images'], $withCount = []): array
//    {
//        if (!isset($filters['status'])) {
//            $filters['status'] = 'active';
//        }
//
//        $filters['exists'] = $exists;
//        $user = auth()->guard('sanctum')->user();
//
//        $query = config('flux-items.models.item')::query()
//            ->applyFilters(new ItemFilter(), $filters);
//
//        if (empty($filters)) {
//            $query->latest('items.id');
//        }
//
//        $maxPrice = 0;
//
//        if (isset($filters['with_max_price'])) {
//            unset($filters['lowest_price']);
//            unset($filters['highest_price']);
//            unset($filters['price_to']);
//            unset($filters['price_from']);
//            unset($filters['exists']);
//
//            $sub_query = config('flux-items.models.item')::query()
//                ->applyFilters(new ItemFilter(), $filters);
//
//            if (!$this->joined($sub_query, 'rent_item_prices')) {
//                $sub_query = $sub_query->join('rent_item_prices', 'items.id', '=', 'rent_item_prices.item_id');
//            }
//
//            $sub_query = $sub_query->toBase();
//
//            $minPriceSubQuery = $sub_query
//                ->selectRaw('MIN(rent_item_prices.price) as min_price, rent_item_prices.item_id')
//                ->groupBy('rent_item_prices.item_id');
//
//            $maxPrice = Cache::remember("max-price-" . json_encode($filters), 3600, function () use ($minPriceSubQuery) {
//                DB::statement('SET session sql_log_bin = 0;');
//                $maxPrice = DB::table(DB::raw("({$minPriceSubQuery->toSql()}) as sub"))
//                    ->mergeBindings($minPriceSubQuery)
//                    ->max('min_price');
//                DB::statement('SET session sql_log_bin = 1;');
//                return $maxPrice;
//            });
//        }
//
//        $query = $query->selectRaw("items.id, items.name, items.slug,items.is_busy, items.is_required_deposit,
//         items.is_hit, items.user_id, items.store_id")
//            ->with(['images', 'rentTypes.pivot.prices', 'user'])
//            ->when(count($withCount), fn($query) => $query->withCount($withCount))
//            ->when(isset($filters['user_id']), fn($query) => $query->addSelect('items.status'))
//            ->withExists(['favorites as is_favorite' => function ($query) use ($user) {
//                return $query->where('user_id', $user?->id);
//            }])
//            ->groupBy('items.id');
//
//        DB::statement('SET session sql_log_bin = 0;');
//
//        $items = $query->paginate($filters['per_page'] ?? 20)
//            ->appends(\request()->except('page'));
//
//        DB::statement('SET session sql_log_bin = 1;');
//
//        return [
//            'max_price' => $maxPrice,
//            'items' => $items,
//        ];
//    }

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

    private function addToQueryMinMainPriceAndType($query)
    {

        if (!$this->joined($query, 'rent_item_prices')) {
            $query = $query->leftJoin('rent_item_prices', 'items.id', '=', 'rent_item_prices.item_id');
        }

        return $query->leftJoin('rent_types', 'rent_types.id', '=', 'rent_type_id')
            ->addSelect(DB::raw("min(CAST(rent_item_prices.price as unsigned)) as main_min_price, rent_type_id, rent_types.slug as rent_type"))
            ->whereNotNull('items.user_id')
            ->groupBy('items.id');
    }
}
