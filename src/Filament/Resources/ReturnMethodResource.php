<?php

namespace Nurdaulet\FluxItems\Filament\Resources;

use Nurdaulet\FluxItems\Filament\Resources\ReturnMethodResource\Pages;
use Nurdaulet\FluxItems\Filament\Resources\ReturnMethodResource\RelationManagers;
use Nurdaulet\FluxItems\Models\ReturnMethod;
use Filament\Forms;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReturnMethodResource extends Resource
{
    use Translatable;

    protected static ?string $model = ReturnMethod::class;

    protected static ?string $modelLabel = 'метод';
    protected static ?string $pluralModelLabel = 'Способы возвращения';

    protected static ?string $navigationIcon = 'heroicon-o-upload';

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
            'index' => Pages\ListReturnMethods::route('/'),
            'create' => Pages\CreateReturnMethod::route('/create'),
            'edit' => Pages\EditReturnMethod::route('/{record}/edit'),
        ];
    }
}
