<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ComplaintItemResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\ComplaintItemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComplaintItems extends ListRecords
{
    protected static string $resource = ComplaintItemResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
