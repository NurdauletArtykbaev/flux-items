<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ConditionResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ConditionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCondition extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = ConditionResource::class;
    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
