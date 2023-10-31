<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ReturnMethodResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ReturnMethodResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReturnMethods extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    protected static string $resource = ReturnMethodResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
