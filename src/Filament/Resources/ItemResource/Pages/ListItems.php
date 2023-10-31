<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ItemResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ItemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItems extends ListRecords
{
    protected static string $resource = ItemResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
