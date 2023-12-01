<?php
namespace Nurdaulet\FluxItems\Filament\Resources\PropertyResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Nurdaulet\FluxItems\Filament\Resources\PropertyResource;

class EditProperty extends EditRecord
{
    protected static string $resource = PropertyResource::class;
    use EditRecord\Concerns\Translatable;

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
