<?php

namespace Nurdaulet\FluxItems\Filament\Resources\PromotionGroupResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\PromotionGroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPromotionGroups extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    protected static string $resource = PromotionGroupResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
