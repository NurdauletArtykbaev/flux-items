<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ProtectMethodResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ProtectMethodResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProtectMethod extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    protected static string $resource = ProtectMethodResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
