<?php

namespace Nurdaulet\FluxItems\Services;

use Algolia\AlgoliaSearch\SearchClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AlgoliaService
{

    protected $index;
    private $algoliaAnalyticsUrl = 'https://analytics.de.algolia.com/2/';

    public function __construct()
    {
        $client = SearchClient::create(
            config('services.algolia.app_id'),
            config('services.algolia.secret')
        );
        $this->index = $client->initIndex(config('services.algolia.index'));
    }

    public function delete($id)
    {
        $this->index->deleteObject($id);
    }

    public function getPopularHits()
    {
        $params = [
            'facets' => ['user_query'], // поле, по которому будем сгруппировать результаты
            'facetFilters' => [['user_query:*']], // фильтр для всех значений поля user_query
            'attributesToRetrieve' => [], // не получаем дополнительные атрибуты
            'hitsPerPage' => 0,
//            'facetFilters' => ['category:popular'], // фильтр для популярных запросов
//            'attributesToRetrieve' => ['query'], // получаем только поле 'query'
//            'hitsPerPage' => 10, // количество получаемых результатов
        ];
        return $this->index->getTop('', $params);
    }

    public function saveSynonym($synonyms = [])
    {

        $this->index->clearSynonyms([
            'forwardToReplicas' => false
        ]);
        $this->index->saveSynonyms($synonyms);
    }

    public function search($q)
    {

    }

    public function saveObjects($data)
    {

        $this->index->saveObjects(
            $data
        );
    }

    public function saveObject($data)
    {
        $this->index->saveObject(
            $data
        );
    }

    public function topSearches()
    {
        try {
            $bannedWords = Cache::remember("banned-words", 3600, function () {
                return config('flux-items.models.banned_top_search_word')::get()->pluck('word')->toArray();
            });
            $words = Cache::remember("top-searches", 86400, function () {
                $data = Http::withHeaders([
                    'X-Algolia-API-Key' => config('services.algolia.analytics_secret'),
                    'X-Algolia-Application-Id' => config('services.algolia.app_id'),
                ])->get($this->algoliaAnalyticsUrl . '/searches?index=' . config('services.algolia.index') . '&limit=15')->json();
                $data = $data['searches'];
                $data = array_column($data, 'search');
                $data = array_filter($data, fn($item) => !empty($item));
                return array_values($data);
            });
            return array_values(array_diff($words, $bannedWords));
        } catch (\Exception $exception) {
            Log::channel('dev')->alert('algolia top search not working');
            return [];
        }
    }
}
