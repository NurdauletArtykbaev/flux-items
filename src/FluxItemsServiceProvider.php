<?php

namespace Nurdaulet\FluxItems;


use Nurdaulet\FluxItems\Filament\Resources\BannerResource;
use Nurdaulet\FluxItems\Filament\Resources\CityResource;
use Nurdaulet\FluxItems\Helpers\StringFormatterHelper;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Nurdaulet\FluxItems\Helpers\TextConverterHelper;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\ServiceProvider;
use Nurdaulet\FluxItems\Models\City;
use Nurdaulet\FluxItems\Observers\CityObserver;
use Nurdaulet\FluxItems\Services\ItemFacadeService;

class FluxItemsServiceProvider extends ServiceProvider
{

    public function boot()
    {
//        City::observe(new CityObserver());

        if ($this->app->runningInConsole()) {
            $this->publishConfig();
            $this->publishMigrations();
        }

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/flux-items.php',
            'flux-base'
        );
        $this->app->bind('textConverter', TextConverterHelper::class);
//        $this->app->bind('stringFormatter', StringFormatterHelper::class);
        $this->app->bind('items', ItemFacadeService::class);
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/flux-items.php' => config_path('flux-items.php'),
        ], 'flux-items-config');


        if (!file_exists(config_path('flux-items.php'))) {
            Artisan::call('vendor:publish', ['--tag' => 'flux-items-config']);
        }
    }

    protected function publishMigrations()
    {

        $this->publishes([
            __DIR__ . '/../database/migrations/check_banned_top_search_words_table.php.stub' => $this->getMigrationFileName(0,'check_flux_items_banned_top_search_words_table.php'),
            __DIR__ . '/../database/migrations/check_catalog_item_table.php.stub' => $this->getMigrationFileName(1,'check_flux_items_catalog_item_table.php'),
            __DIR__ . '/../database/migrations/check_complaint_reasons_table.php.stub' => $this->getMigrationFileName(2,'check_flux_items_complaint_reasons_table.php'),
            __DIR__ . '/../database/migrations/check_complaint_item_table.php.stub' => $this->getMigrationFileName(3,'check_flux_items_complaint_item_table.php'),
            __DIR__ . '/../database/migrations/check_conditions_table.php.stub' => $this->getMigrationFileName(4,'check_flux_items_conditions_table.php'),
            __DIR__ . '/../database/migrations/check_favorite_item_table.php.stub' => $this->getMigrationFileName(5,'check_flux_items_favorite_item_table.php'),
            __DIR__ . '/../database/migrations/check_image_item_table.php.stub' => $this->getMigrationFileName(6,'check_flux_items_image_item_table.php'),
            __DIR__ . '/../database/migrations/check_item_city_table.php.stub' => $this->getMigrationFileName(7,'check_flux_items_item_city_table.php'),
            __DIR__ . '/../database/migrations/check_protect_methods_table.php.stub' => $this->getMigrationFileName(8,'check_flux_items_protect_methods_table.php'),
            __DIR__ . '/../database/migrations/check_receive_methods_table.php.stub' => $this->getMigrationFileName(9,'check_flux_items_receive_methods_table.php'),
            __DIR__ . '/../database/migrations/check_protect_method_item_table.php.stub' => $this->getMigrationFileName(10,'check_flux_items_protect_method_item_table.php'),
            __DIR__ . '/../database/migrations/check_receive_method_item_table.php.stub' => $this->getMigrationFileName(11,'check_flux_items_receive_method_item_table.php'),
            __DIR__ . '/../database/migrations/check_rent_item_prices_table.php.stub' => $this->getMigrationFileName(12,'check_flux_items_rent_item_prices_table.php'),
            __DIR__ . '/../database/migrations/check_rent_type_item_table.php.stub' => $this->getMigrationFileName(13,'check_flux_items_rent_type_item_table.php'),
            __DIR__ . '/../database/migrations/check_return_methods_table.php.stub' => $this->getMigrationFileName(14,'check_flux_items_return_methods_table.php'),
            __DIR__ . '/../database/migrations/check_return_method_item_table.php.stub' => $this->getMigrationFileName(15,'check_flux_items_return_method_item_table.php'),
            __DIR__ . '/../database/migrations/check_top_items_table.php.stub' => $this->getMigrationFileName(16,'check_flux_items_top_items_table.php'),
            __DIR__ . '/../database/migrations/check_view_history_item_table.php.stub' => $this->getMigrationFileName(17,'check_flux_items_view_history_item_table.php'),
            __DIR__ . '/../database/migrations/check_items_table.php.stub' => $this->getMigrationFileName(18,'check_flux_items_items_table.php'),
        ], 'flux-item-migrations');
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     */
    protected function getMigrationFileName($index,string $migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make([$this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR])
            ->flatMap(fn($path) => $filesystem->glob($path . '*_' . $migrationFileName))
            ->push($this->app->databasePath() . "/migrations/{$timestamp}{$index}_{$migrationFileName}")
            ->first();
    }
}
