<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ConditionResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ConditionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConditions extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    protected static string $resource = ConditionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
