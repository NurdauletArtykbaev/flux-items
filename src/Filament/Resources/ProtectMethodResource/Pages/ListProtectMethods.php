<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ProtectMethodResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ProtectMethodResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProtectMethods extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    protected static string $resource = ProtectMethodResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
