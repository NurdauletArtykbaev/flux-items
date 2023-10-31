<?php

namespace Nurdaulet\FluxItems\Filament\Resources;

use Nurdaulet\FluxItems\Filament\Resources\ReceiveMethodResource\Pages;
use Nurdaulet\FluxItems\Filament\Resources\ReceiveMethodResource\RelationManagers;
use Nurdaulet\FluxItems\Models\ReceiveMethod;
use Filament\Forms;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReceiveMethodResource extends Resource
{
    use Translatable;

    protected static ?string $model = ReceiveMethod::class;


    protected static ?string $modelLabel = 'способ';
    protected static ?string $pluralModelLabel = 'Способы получения';

    protected static ?string $navigationIcon = 'heroicon-o-save';

    public static function getTranslatableLocales(): array
    {
        return config('flux-items.languages');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->maxLength(255)
                    ->label(trans('admin.name')),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->label(trans('admin.is_active')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(trans('admin.name')),
                Tables\Columns\BooleanColumn::make('is_active')->label(trans('admin.is_active')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->label(trans('admin.created_at')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->label(trans('admin.updated_at')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReceiveMethods::route('/'),
            'create' => Pages\CreateReceiveMethod::route('/create'),
            'edit' => Pages\EditReceiveMethod::route('/{record}/edit'),
        ];
    }
}
