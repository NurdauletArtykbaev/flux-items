<?php

namespace App\Filament\Resources\ValueResource\Pages;

use App\Filament\Resources\ValueResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

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
