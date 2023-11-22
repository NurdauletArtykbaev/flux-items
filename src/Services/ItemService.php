<?php

namespace Nurdaulet\FluxItems\Services;

use Nurdaulet\FluxCatalog\Http\Resources\CatalogsResource;
use Nurdaulet\FluxItems\Helpers\ItemHelper;
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

class ItemService
{

    public function __construct(private ItemRepository $itemRepository, private StoreEmployeeService $storeEmployeeService)
    {
    }

    public function store(Request $request)
    {
        $data = $this->prepareCreateProductData($request);
        $item = $this->itemRepository->create($data);
        $this->productRelationshipSave($item, $request->toArray());
    }

    public function search($q, $cityId = null): array
    {
        [$items, $facets] = $this->getSearchProduct($q, $cityId);

        $categories = $this->getFacetCategories($facets);
        return [
            'catalogs' => CatalogsResource::collection($categories),
            'items' => ProductsResource::collection($items),
        ];
    }

    public function getSimilarItems($itemId, $data = [])
    {
        $cityId = $data['city_id'] ?? null;
        $item = config('flux-items.models.item')::with('catalogs')
            ->applyFilters(new ItemFilter(), ['city_id' => $cityId, 'ids' => $itemId,])
            ->first();
        if (!$item?->id) {
            return [];
        }
        $filters = [
            'similar_by_catalog' => $item->catalogs->pluck('id')->toArray(),
            'limit' => 6,
            'is_random' => true,
            'not_id' => $item->id,
        ];

        if (isset($data['lord_items'])) {
            unset($filters['similar_by_catalog']);
            $filters['user_id'] = $item->user_id;
        }

        $filters['city_id'] = $cityId;
        $items = $this->getPaginated($filters);


        if ($items->count() < 4) {
            if (isset($data['lord_items'])) {
                unset($data['similar_by_catalog']);
            } else {
                $item->catalogs->load('bloodline');
                $catalogIds = $item->catalogs->pluck('bloodline')->flatten()->pluck('id')->toArray();
                $filters['similar_by_catalog'] = $catalogIds;
            }
            $filters['city_id'] = $cityId;
            $items = $this->getPaginated($filters);
        }
        return $items;
    }

    public function show($id)
    {
        $relations = [
            'user' => fn($query) => $query->withCount('reviews'),
            'catalogs',
            'images',
            'condition',
            'cities',
            'receiveMethods',
            'returnMethods',
        ];

        $relations[] = ItemHelper::getPriceRelation();
        $item = $this->itemRepository->find($id, $relations, [] , ['reviews']);

        $item->user->ratings_count = $item->user->ratings_count <= 0 ? $item->user->id + 7 : $item->user->ratings_count;
        $item->user->avg_rating = $item->user->avg_rating <= 0 ? null : $item->user->avg_rating;

        if ($item->receiveMethods->isEmpty()) {
            if (is_null($item->address_name)) {
                $receive_method = config('flux-items.models.receive_method')::where('name->ru', 'like', "Доставка")->first();
            } else {
                $receive_method = config('flux-items.models.receive_method')::where('name->ru', 'like', "Самовывоз")->first();
            }
            $receive_method->pivot = [
                'item_id' => $item->id,
                'id' => $receive_method->id,
                'delivery_price' => 0
            ];
            $item->receiveMethods[0] = $receive_method;
        }

        $item->viewHistory()->firstOrCreate();
        $item->viewHistory()->increment('count');

        $item->loadMissing('viewHistory');
        $schema = match ($item->condition_id) {
            2, 1 => 'NewCondition',
            default => 'UsedCondition',
        };
        $item->condition_schema = 'https://schema.org/' . $schema;

        return $item;
    }

    public function findByUser($id, $user, $withCount = [])
    {
        $lordId = $user->id;
        if (config('flux-items.options.use_roles')) {
            $lordId = $this->storeEmployeeService->getLordId($user);
        }

        $filters = [
            'user_id' => $lordId
        ];
        $relations = ['user',
            'catalogs',
//            'catalogs.rentalDayTypes',
            'images',
            'receiveMethods',
            'returnMethods',
            'condition',
            'protectMethods',
            'viewHistory',
            'cities'
        ];
        $relations[] = ItemHelper::getPriceRelation();
        return $this->itemRepository->find($id, $relations, $filters, $withCount);
    }

    public function delete($id, $user)
    {
        $lordId = $user->id;
        if (config('flux-items.options.use_roles')) {
            $lordId = $this->storeEmployeeService->getLordId($user);
        }
        return $this->itemRepository->delete($id, $lordId);
    }

    public function update($id, $filters, $data)
    {
        $item = $this->itemRepository->find($id, $filters);

        $item->update($data);
        $this->productRelationshipSave($item, $data);
    }

    public function get($filters = [])
    {
        if (!isset($filters['status'])) {
            $filters['status'] = 'active';
        }
        return $this->itemRepository->get($filters);
    }

    public function getPaginated($filters = [], $relations = ['images', 'cities'], $exists = ['images'], $withCount = [])
    {
        if (!isset($filters['status'])) {
            $filters['status'] = 'active';
        }
        if (isset($relations)) {
            $relations[] = ItemHelper::getPriceRelation();
        }
        return $this->itemRepository->getPaginated($filters, $relations, $exists, $withCount);
    }

    public function getMaxPrice($filters = [])
    {
        return Cache::remember("max-price-new-" . json_encode($filters), 3600, function () use ($filters) {
            return $this->itemRepository->getMaxPrice($filters);
        });
    }

    private function productRelationshipSave($item, $data): void
    {
        $images = config('flux-items.models.image_item')::where('item_id', $item->id)->get();
        if (isset($data['images'])) {
            if (!empty($data['images'])) {
                $deletedImageIds = array_diff($images->pluck('id')->toArray(), $data['images']);
                if (!empty($deletedImageIds)) {
                    $deleteImages = $images->whereIn('id', $deletedImageIds);
                    foreach ($deleteImages as $deleteImage) {
                        if (Storage::disk('s3')->exists($deleteImage->image)) {

                            Storage::disk('s3')->delete($deleteImage->image);
                            Storage::disk('s3')->delete($deleteImage->webp);
                        }
                    }
                    config('flux-items.models.image_item')::whereIn('id', $deletedImageIds)->delete();
                }
            } else {
                $this->deleteImage($item->id, $images);
            }
        }

        if (isset($data['temp_images']) && !empty($data['temp_images'])) {
            $tempImages = config('flux-items.models.temprory_image')::whereIn('id', $data['temp_images'])->get();
            $tempImages = $tempImages->toArray();
            $tempImages = array_map(fn($tempImage) => ['image' => $tempImage['image'], 'webp' => $tempImage['webp']], $tempImages);
            $item->images()->createMany($tempImages);

//            $deletedImageIds = array_diff($images->pluck('id')->toArray(), $request->temp_images);
//            if (!empty($deletedImageIds)) {
//                $deleteImages = $images->whereIn('id', $deletedImageIds);
//                foreach ($deleteImages as $deleteImage) {
//                    if (Storage::disk('s3')->exists($deleteImage->image)) {
////                        Storage::disk('s3')->delete($deleteImage->image);
//                    }
//                    if (Storage::disk('s3')->exists($deleteImage->image_webp)) {
////                        Storage::disk('s3')->delete($deleteImage->image_webp);
//                    }
//                }
//            }
//            TemproryImage::where('user_id', $request->user()->id)->delete();
            config('flux-items.models.temprory_image')::where('user_id', $item->user_id)->delete();
        }
        if (isset($data['catalogs']) && !empty($data['catalogs'])) {
            $item->catalogs()->sync($data['catalogs']);
        }
        if (isset($data['city_ids']) && !empty($data['city_ids'])) {
            $item->cities()->sync($data['city_ids']);
        }


        if (isset($data['rent_prices'])) {
            DB::beginTransaction();

            config('flux-items.models.rent_type_item')::where('item_id', $item->id)
                ->whereNotIn('rent_type_id', Arr::pluck($data['rent_prices'], 'id'))
                ->delete();

            $rentTypes = config('flux-items.models.rent_type')::whereIn('id', Arr::pluck($data['rent_prices'], 'id'))
                ->get();
            $isRentDaily = config('flux-items.options.is_rent_daily');
            foreach ($data['rent_prices'] as &$rentPrice) {
                $rentType = $rentTypes->where('id', $rentPrice['id'])?->first();
                if (empty($rentType?->slug)) {
                    continue;
                }
                if ($isRentDaily) {
                    $this->recalculateIsDaidyPriceAndSave($item->id, $rentPrice['prices'], $rentType);
                } else {
                    config('flux-items.models.rent_type_item')::updateOrCreate([
                        'rent_type_id' => $rentType->id,
                        'item_id' => $item->id
                    ], [
                        'rent_type_id' => $rentType->id,
                        'item_id' => $item->id,
                        'price' => $rentPrice['price'],
                        'old_price' => $rentPrice['old_price'] ?? null
                    ]);
                }

            }
            DB::commit();
        }

        if (isset($data['methods_receiving']) && !empty($data['methods_receiving'])) {
            $item->receiveMethods()->syncWithPivotValues(array_values(array_map(fn($receivMethods) => $receivMethods['id'], $data['methods_receiving'])), ['delivery_price' => 0]);;
        }
        if (isset($data['methods_return']) && !empty($data['methods_return'])) {
            $item->returnMethods()->sync($data['methods_return']);;
        }
        if (isset($data['address']) && !empty($data['address'])) {
            $item->address_name = $data['address'];
            $item->lat = $data['lat'] ?? null;
            $item->lng = $data['lng'] ?? null;
            $item->save();
        }
        if (isset($data['protect_methods']) && !empty($data['protect_methods']) && is_array($data['protect_methods'])) {
            $item->protectMethods()->sync($data['protect_methods']);
        }
    }

    public function recalculateIsDaidyPriceAndSave($itemId, $prices, $rentType)
    {

        foreach ($prices as &$price) {
            unset($price['created_at']);
            unset($price['updated_at']);
            unset($price['id']);
            $price['rent_type_id'] = $rentType->id;
            $price['price'] = (int)$price['price'];
            $price['weekend_price'] = $price['weekend_price'] ?? null;
            $price['from'] = $price['from'] ?? null;
            $price['to'] = $price['to'] ?? null;
            $price['item_id'] = $itemId;
        }
        if (count($prices)) {
            config('flux-items.models.rent_item_price')::where('item_id', $itemId)
                ->where('rent_type_id', $rentType->id)->delete();
            config('flux-items.models.rent_item_price')::insert($prices);
            config('flux-items.models.rent_type_item')::updateOrCreate([
                'rent_type_id' => $rentType->id,
                'item_id' => $itemId
            ], [
                'rent_type_id' => $rentType->id,
                'item_id' => $itemId
            ]);
        }
    }

    private function prepareCreateProductData($request)
    {
        $data = $request->validated();
        $user = $request->user();
        $lordId = $user->id;
        if (config('flux-items.options.use_roles')) {
            $lordId = $this->storeEmployeeService->getLordId($user);
        }
        $data['user_id'] = $lordId;
        $data['is_required_deposit'] = !!($request->get('is_required_deposit') == 'true');
        return $data;
    }

    private function getSearchProduct($q, $cityId): array
    {
        try {
            $result = config('flux-items.models.item')::search($q)
                ->with(['clickAnalytics' => true, 'enablePersonalization' => false, 'facets' => ['catalog_id']])
                ->when($cityId, fn($query) => $query->whereHas('cities', fn($query) => $query->where('cities.id', $cityId)))
                ->take(50)->raw();
            $resultObjectIds = collect($result['hits'])->pluck('objectID')
                ->unique()
                ->whereNotNull()
                ->toArray();
            $query = config('flux-items.models.item')::query();
            if (!empty($resultObjectIds) && count($resultObjectIds)) {
                $query = $query->where(fn($query) => $query->orWhereIn('items.id', $resultObjectIds)
                );
                $query = $query->orderByRaw(\DB::raw("FIELD(items.id, " . implode(',', $resultObjectIds) . " )"));
            }
            return [$query->with(['catalogs:id,name'])->limit(20)->get(), $result['facets']];
        } catch (\Exception $exception) {

            Log::channel('dev')->alert('Algolia error');
        }
        $query = config('flux-items.models.item')::query()
            ->where(function ($query) use ($q) {
                return $query->where('items.name', 'like', "%$q%")
                    ->orWhere('items.description', 'like', "%$q%");
            });
        return [$query->with(['catalogs:id,name'])->limit(20)->get(), []];
    }

    private function getFacetCategories($catalogFacets = []): array
    {
        $catalogs = [];
        if (isset($catalogFacets['catalog_id'])) {
            $dbCatalogs = config('flux-items.models.catalog')::whereIn('id', array_keys($catalogFacets['catalog_id']))
                ->orderByRaw(\DB::raw("FIELD(id, " . implode(',', $catalogFacets['catalog_id']) . " )"))->get();
            $catalogIds = array_keys($catalogFacets['catalog_id']);
            foreach ($catalogIds as $catalogId) {
                $catalog = $dbCatalogs->where('id', $catalogId)->first();
                if ($catalog?->id) {
                    $catalogs[] = $catalog;
                }
            }
        }
        return $catalogs;
    }

    private function deleteImage($itemId, $images)
    {
        if ($images->count()) {
            foreach ($images as $image) {
                if (Storage::disk('s3')->exists($image->image)) {
                    Storage::disk('s3')->delete($image->image);
                    Storage::disk('s3')->delete($image->webp);
                }
            }
            config('flux-items.models.image_item')::where('item_id', $itemId)->delete();
        }
    }

}
