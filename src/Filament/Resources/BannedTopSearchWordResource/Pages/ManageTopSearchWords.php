<?php

namespace Nurdaulet\FluxItems\Filament\Resources\BannedTopSearchWordResource\Pages;

use Nurdaulet\FluxItems\Filament\Resources\BannedTopSearchWordResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTopSearchWords extends ManageRecords
{
    protected static string $resource = BannedTopSearchWordResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                return $data;
            }),
        ];
    }
}
