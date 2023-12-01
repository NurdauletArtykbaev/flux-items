<?php

namespace Nurdaulet\FluxItems\Filament\Resources\PropertyResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Nurdaulet\FluxItems\Filament\Resources\PropertyResource;

class ListProperty extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = PropertyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
