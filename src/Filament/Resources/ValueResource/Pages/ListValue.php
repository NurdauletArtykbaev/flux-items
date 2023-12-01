<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ValueResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Nurdaulet\FluxItems\Filament\Resources\ValueResource;

class ListValue extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = ValueResource::class;

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
