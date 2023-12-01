<?php
namespace Nurdaulet\FluxItems\Filament\Resources\ValueResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Nurdaulet\FluxItems\Filament\Resources\ValueResource;

class EditValue extends EditRecord
{
    protected static string $resource = ValueResource::class;
    use EditRecord\Concerns\Translatable;

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
