<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ValueResource\Pages;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Nurdaulet\FluxItems\Filament\Resources\ValueResource;

class CreateValue extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = ValueResource::class;
    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
