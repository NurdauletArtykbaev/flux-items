<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ReturnMethodResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ReturnMethodResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReturnMethod extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    protected static string $resource = ReturnMethodResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
