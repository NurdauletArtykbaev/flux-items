<?php

namespace Nurdaulet\FluxItems\Filament\Resources\PropertyResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Nurdaulet\FluxItems\Filament\Resources\PropertyResource;

class CreateProperty extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = PropertyResource::class;
    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
