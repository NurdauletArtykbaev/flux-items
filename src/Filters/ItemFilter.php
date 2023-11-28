<?php

namespace Nurdaulet\FluxItems\Filters;

use Exception;
use Illuminate\Support\Facades\Cache;
use Nurdaulet\FluxCatalog\Repositories\CatalogRepository;

class ItemFilter extends ModelFilter
{
    public function city_id($value)
    {
        if (empty($value)) {
            return $this;
        }
        return $this->builder->whereHas('cities', fn($query) => $query->where('cities.id', $value));
    }

    public function is_hit($value)
    {
        if (empty($value)) {
            return $this;
        }
        return $this->builder->where('items.is_hit', 1);
    }

    public function is_required_deposit($value)
    {
        if (empty($value)) {
            return $this;
        }
        return $this->builder->where('items.is_required_deposit', 1);
    }

    public function user_id($value)
    {
        if (empty($value)) {
            return $this;
        }
        return $this->builder->where('items.user_id', $value);
    }

    public function status($value)
    {
        if ($value == 'all') {
            return $this;
        }
        return $this->builder->where('items.is_active', $value == 'active' ? 1 : 0);
    }

    public function q($value)
    {
        if (empty($value)) {
            return $this;
        }
        try {
            $result = config('flux-items.models.item') ::search($value)
                ->with(['clickAnalytics' => true, 'enablePersonalization' => false])
                ->when(request()->city_id, $this->builder->whereHas('cities', fn($query) => $query->where('cities.id', request()->city_id)))
                ->take(2000)->raw();

            $resultObjectIds = collect($result['hits'])->pluck('objectID')
                ->unique()
                ->whereNotNull()
                ->toArray();
            unset($result);

            if (!empty($resultObjectIds) && count($resultObjectIds)) {
                $this->builder->where(fn($query) => $query->orWhereIn('items.id', $resultObjectIds)
                );
                $this->builder
                    ->orderByRaw(\DB::raw("FIELD(items.id, " . implode(',', $resultObjectIds) . " )"));
                return $this;
            }
        } catch (Exception $exception) {

        }

        $this->builder->where(function ($query) use ($value) {
            return $query->where('items.name', 'like', "%$value%")
                ->orWhere('items.description', 'like', "%$value%");
        });
        return $this;
    }

    public function name($value)
    {
        return $this->builder->where(function ($query) use ($value) {
            return $query->where('items.name', 'like', "%$value%")
                ->orWhere('items.description', 'like', "%$value%");
        });
    }

    public function catalog_id($value)
    {
        if (empty($value)) {
            return $this;
        }
        $catalogIds = Cache::remember("catalog-descendants-ids-" . $value, 3600, function () use ($value) {
            return (new CatalogRepository())->descendantsAndSelfIds($value);
        });
        if (!$this->joined($this->builder, 'catalog_item')) {
            $this->builder->join('catalog_item', 'items.id', '=', 'catalog_item.item_id');
        }
        return $this->builder->whereIn('catalog_item.catalog_id', $catalogIds);
    }

    public function catalog_slug($value)
    {
        if (empty($value)) {
            return $this;
        }
        $catalogIds = Cache::remember("catalog-descendants-ids-" . $value, 3600, function () use ($value) {
            return (new CatalogRepository())->descendantsAndSelfIdsBySlug($value);
        });

        if (!$this->joined($this->builder, 'catalog_item')) {
            $this->builder->join('catalog_item', 'items.id', '=', 'catalog_item.item_id');
        }
        return $this->builder->whereIn('catalog_item.catalog_item', $catalogIds);
    }

    public function ids($value)
    {
        if (empty($value)) {
            return $this->builder->limit(0);
        }

        if (is_array($value)) {
            return $this->builder->whereIn('items.id', $value)
                ->orderBy(\DB::raw("FIELD(items.id, " . implode(',', $value) . " )"));
        }
        return $this->builder->where('items.id', $value);
    }

    public function not_id($value)
    {
        if (empty($value)) {
            return $this;
        }

        if (is_array($value)) {
            return $this->builder->whereNotIn('items.id', $value);
        }
        return $this->builder->where('items.id', '<>', $value);
    }

    public function lowest_price($value)
    {
        if (!$value) {
            return $this;
        }

        return $this->builder
            ->selectSub(function ($query) {
                $query->select('price')
                    ->from('rent_item_prices')
                    ->whereColumn('item_id', 'items.id')
                    ->orderBy('price')
                    ->limit(1);
            }, 'min_price')
//            ->whereRaw('min_price> 0')
            ->groupBy('items.id')
            ->orderBy('min_price', 'ASC');
    }

    public function highest_price($value)
    {
        if (!$value) {
            return $this;
        }
        return $this->builder
            ->selectSub(function ($query) {
                $query->select('price')
                    ->from('rent_item_prices')
                    ->whereColumn('item_id', 'items.id')
                    ->orderBy('price')
                    ->limit(1);
            }, 'min_price')
            ->groupBy('items.id')
            ->orderBy('min_price', 'DESC');
    }

    public function newest($value)
    {
        if (empty($value)) {
            return $this;
        }
        return $this->builder->latest('items.id');
    }

    public function exists($value)
    {
        if ($value == null) {
            return  $this;
        }
        if (empty($value)) {
            return  $this->builder->has('images');
        }
        if (is_array($value)) {
            foreach ($value as $valueItem) {
                $this->builder = $this->builder->has($valueItem);
            }
        }
        return $this;
    }

    public function price_from($value)
    {
        $value = (int)$value;
        if (empty($value)) {
            return $this;
        }

        if (!$this->joined($this->builder, 'rent_item_prices')) {
            $this->builder->leftJoin('rent_item_prices', 'items.id', '=', 'rent_item_prices.item_id');
        }

        return $this->builder
            ->groupBy('items.id')
            ->havingRaw("min(rent_item_prices.price) >=  $value");
    }

    public function price_to($value)
    {

        $value = (int)$value;
        if (empty($value)) {
            return $this;
        }
        if (!$this->joined($this->builder, 'rent_item_prices')) {
            $this->builder->leftJoin('rent_item_prices', 'items.id', '=', 'rent_item_prices.item_id');
        }
        return $this->builder
            ->groupBy('items.id')
            ->havingRaw("min(rent_item_prices.price) <=  $value");
    }

    public function in_random($value)
    {
        return $this->builder->inRandomOrder();
    }

    public function receive_methods($value)
    {

        if (empty($value)) {
            return $this;
        }
        if (is_array($value)) {
            return $this->builder->whereHas('receiveMethods', function ($query) use ($value) {
                $query->whereIn('receive_method_id', $value);
            })->with('receiveMethods', fn($query) => $query->whereIn('receiveMethods.receive_method_id', $value));
        }
        return $this->builder->whereHas('receiveMethods', function ($query) use ($value) {
            $query->where('receive_method_id', $value);
        })->with('receiveMethods', fn($query) => $query->where('receiveMethods.receive_method_id', $value));
    }

    public function similar_by_catalog($value)
    {
        if (empty($value)) {
            return $this;
        }

        return $this->builder->whereHas('catalogs', function ($query) use ($value) {
            if (is_array($value)) {
                $query->whereIn('catalogs.id', $value);
            } else {
                $query->where('catalogs.id', $value);

            }
        });
    }

    public function limit($value)
    {
        return $this->builder->limit($value ?? 20);
    }


    public function is_random($value)
    {
        return $this->builder->inRandomOrder();
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
