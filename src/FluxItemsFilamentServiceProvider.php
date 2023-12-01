<?php

namespace Nurdaulet\FluxItems;


use Filament\PluginServiceProvider;
use Nurdaulet\FluxItems\Filament\Resources\ConditionResource;
use Nurdaulet\FluxItems\Filament\Resources\BannedTopSearchWordResource;
use Nurdaulet\FluxItems\Filament\Resources\BannerResource;
use Nurdaulet\FluxItems\Filament\Resources\ComplaintItemResource;
use Nurdaulet\FluxItems\Filament\Resources\ItemResource;
use Nurdaulet\FluxItems\Filament\Resources\PromotionGroupResource;
use Nurdaulet\FluxItems\Filament\Resources\ProtectMethodResource;
use Nurdaulet\FluxItems\Filament\Resources\ReceiveMethodResource;
use Nurdaulet\FluxItems\Filament\Resources\ReturnMethodResource;
use Spatie\LaravelPackageTools\Package;

class FluxItemsFilamentServiceProvider extends PluginServiceProvider
{
    protected array $resources = [
        BannedTopSearchWordResource::class,
        ComplaintItemResource::class,
        ItemResource::class,
        ConditionResource::class,
        ProtectMethodResource::class,
        ReceiveMethodResource::class,
        ReturnMethodResource::class,
        PromotionGroupResource::class,
        PropertyR::class,
    ];


    public function configurePackage(Package $package): void
    {
        $this->packageConfiguring($package);
        $package->name('base-package');
    }
}
