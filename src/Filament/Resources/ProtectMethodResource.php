<?php

namespace Nurdaulet\FluxItems\Filament\Resources;

use Nurdaulet\FluxItems\Filament\Resources\ProtectMethodResource\Pages;
use Nurdaulet\FluxItems\Models\ProtectMethod;
use Filament\Forms;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProtectMethodResource extends Resource
{
    use Translatable;
    protected static ?string $model = ProtectMethod::class;

    protected static ?string $modelLabel = 'метод';
    protected static ?string $pluralModelLabel = 'Способы защиты';

    protected static ?string $navigationIcon = 'heroicon-o-code';
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
            'index' => Pages\ListProtectMethods::route('/'),
            'create' => Pages\CreateProtectMethod::route('/create'),
            'edit' => Pages\EditProtectMethod::route('/{record}/edit'),
        ];
    }
}
