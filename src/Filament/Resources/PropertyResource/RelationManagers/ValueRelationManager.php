<?php

namespace Nurdaulet\FluxItems\Filament\Resources\PropertyResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Nurdaulet\FluxItems\Models\Value;

class ValueRelationManager extends RelationManager
{

    protected static string $relationship = 'values';
    protected static ?string $modelLabel = 'Значение';
    protected static ?string $pluralModelLabel = 'Значение';

    protected static ?string $recordTitleAttribute = 'value';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('value')
                    ->label(trans('admin.value')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('value')
                    ->label(trans('admin.value')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn(AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->searchable()
                            ->getSearchResultsUsing(fn(string $search) => Value::whereRaw("concat(value, ' ', 'description') like '%" . $search . "%'")
                                ->orderBy('value','asc')
                                ->limit(50)->selectRaw("id,   concat(description , ': ', value) as info")
                                ->pluck('info', 'id'))
                            ->optionsLimit(1000)
                            ->preload()
                            ->label(trans('admin.value')),

                    ])

                    ->preloadRecordSelect()

            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
