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

class RentalDayTypesRelationManager extends RelationManager
{
    protected static string $relationship = 'rentTypes';
    protected static ?string $modelLabel = 'Цены';
    protected static ?string $pluralModelLabel = 'Цены';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rent_type_id')
                    ->options(config('flux-items.models.rent_type')::get()->pluck('name', 'id')->toArray())
                    ->label(trans('admin.name')),
                Forms\Components\TextInput::make('old_price')
                    ->label(trans('admin.old_price')),
                Forms\Components\TextInput::make('price')
                    ->label(trans('admin.price')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('admin.name')),
                Tables\Columns\TextColumn::make('price')
                    ->label(trans('admin.price')),
                Tables\Columns\TextColumn::make('old_price')
                    ->label(trans('admin.old_price'))
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn(AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->preload()
                            ->label(trans('admin.rent_type')),
                        Forms\Components\TextInput::make('price')
                            ->label(trans('admin.price'))
                            ->required(),
                        Forms\Components\TextInput::make('old_price')
                            ->label(trans('admin.old_price'))
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
