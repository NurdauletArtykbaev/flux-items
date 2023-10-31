<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ItemResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ItemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
