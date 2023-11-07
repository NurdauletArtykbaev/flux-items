<?php

namespace Nurdaulet\FluxItems\Filament\Resources\PromotionGroupResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Nurdaulet\FluxItems\Filament\Resources\PromotionGroupResource;

class CreatePromotionGroup extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = PromotionGroupResource::class;
    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
