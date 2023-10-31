<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ProtectMethodResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ProtectMethodResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProtectMethod extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = ProtectMethodResource::class;

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
