<?php

namespace App\Filament\Resources\ValueResource\Pages;

use App\Filament\Resources\ValueResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListValue extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = ValueResource::class;

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
