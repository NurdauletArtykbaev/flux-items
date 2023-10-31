<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ConditionResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ConditionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCondition extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    protected static string $resource = ConditionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
