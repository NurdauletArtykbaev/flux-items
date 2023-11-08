<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ItemResource\RelationManagers;

use App\Helpers\RentalDayHelper;
use App\Models\RentalDay;
use App\Models\User;
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
    protected static string $relationship = 'rentalDayTypes';
    protected static ?string $modelLabel = 'Цены';
    protected static ?string $pluralModelLabel = 'Цены';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rental_day_type')
                    ->options(RentalDayHelper::getTypeOptions())
                    ->required()
                    ->label(trans('admin.rental_day_type')),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->label(trans('admin.price')),
                Forms\Components\TextInput::make('weekend_price')
                    ->label(trans('admin.weekend_price')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SelectColumn::make('rental_day_type')
                    ->disabled()
                    ->options(RentalDayHelper::getTypeOptions())
                    ->label(trans('admin.rental_day_type')),
                Tables\Columns\TextColumn::make('price')
                    ->label(trans('admin.price')),
                Tables\Columns\TextColumn::make('weekend_price')
                    ->label(trans('admin.weekend_price')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        return $data;
                    })
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
