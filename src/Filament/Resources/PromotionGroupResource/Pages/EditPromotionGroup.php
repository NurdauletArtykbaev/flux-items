<?php

namespace Nurdaulet\FluxItems\Filament\Resources\PromotionGroupResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\PromotionGroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPromotionGroup extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    protected static string $resource = PromotionGroupResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
