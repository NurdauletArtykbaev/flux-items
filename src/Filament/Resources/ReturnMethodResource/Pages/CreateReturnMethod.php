<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ReturnMethodResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ReturnMethodResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReturnMethod extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = ReturnMethodResource::class;
    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
