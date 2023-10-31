<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ItemResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReceiveMethodsRelationManager extends RelationManager
{
    protected static string $relationship = 'receiveMethods';
    protected static ?string $modelLabel = 'способ';
    protected static ?string $pluralModelLabel = 'Способы получения';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)->label(trans('admin.name')),
                Forms\Components\TextInput::make('delivery_price')
                    ->required()
                    ->label(trans('admin.delivery_price')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('admin.name')),
                Tables\Columns\TextColumn::make('delivery_price')
                    ->label(trans('admin.delivery_price')),

            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn(AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->preload()
                            ->label(trans('admin.receive_method')),
                        Forms\Components\TextInput::make('delivery_price')
                            ->label(trans('admin.delivery_price'))
                            ->required(),
                    ])
            ])
            ->actions([
                // ...
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                // ...
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
