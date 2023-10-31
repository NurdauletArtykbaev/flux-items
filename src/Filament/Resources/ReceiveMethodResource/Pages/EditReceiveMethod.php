<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ReceiveMethodResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ReceiveMethodResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReceiveMethod extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    protected static string $resource = ReceiveMethodResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
