<?php

namespace Nurdaulet\FluxItems\Observers;

use Nurdaulet\FluxItems\Facades\TextConverter;
use Nurdaulet\FluxItems\Services\AlgoliaService;
use Exception;
use Illuminate\Support\Facades\Log;

class ItemObserver
{
    public function created($ad)
    {
        if ($ad->status && app()->isProduction()) {
            try {
                (new AlgoliaService())->saveObject($ad->toSearchableArray());

            } catch (Exception $exception) {
                Log::alert('Algolia error');
            }
        }
        $ad->slug = TextConverter::translate($ad->name) . '-' . $ad->id;
        $ad->save();
    }

    public function updated($ad)
    {
        if (app()->isProduction()) {
            try {
                if ($ad->status) {
                    (new AlgoliaService())->saveObject($ad->toSearchableArray());
                } else {
                    (new AlgoliaService())->delete($ad->id);
                }
            } catch (Exception $exception) {
                Log::alert('Algolia error');

            }
        }
        if ($ad->slug != TextConverter::translate($ad->name) . '-' . $ad->id) {
            Log::channel('dev')->info('observer update ' . $ad->id);

            $ad->slug = TextConverter::translate($ad->name) . '-' . $ad->id;
            $ad->save();
        }

//        ProductDeleteFromCacheFrontJob::dispatch($ad->slug);
    }


    public function deleted($ad)
    {
        if (app()->isProduction()) {
            try {
                (new AlgoliaService())->delete($ad->id);
            } catch (Exception $exception) {
                Log::channel('dev')->alert('Algolia error');

            }
        }

//        $ad->images()->delete();
//        $ad->categories()->sync([]);
//        $ad->rentTypes()->sync([]);
//        $ad->allPrices()->delete();
//        $ad->receiveMethods()->sync([]);
//        $ad->returnMethods()->sync([]);
//        $ad->protectMethods()->sync([]);
//        $ad->viewHistory()->delete();
//        $ad->reviews()->delete();

    }
}
