<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ReceiveMethodResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ReceiveMethodResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReceiveMethods extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    protected static string $resource = ReceiveMethodResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
