<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ReceiveMethodResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ReceiveMethodResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReceiveMethod extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = ReceiveMethodResource::class;

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
