<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ItemResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ItemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;

    protected function afterCreate(): void
    {

    }
}
