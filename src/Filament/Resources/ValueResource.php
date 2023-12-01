<?php

namespace Nurdaulet\FluxItems\Filament\Resources;

use Nurdaulet\FluxItems\Filament\Resources\Pages;
use Filament\Forms;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Nurdaulet\FluxItems\Models\Value;

class ValueResource extends Resource
{
    use Translatable;

    protected static ?string $model = Value::class;

    protected static ?string $modelLabel = 'Значение';
    protected static ?string $pluralModelLabel = 'Значение';

    protected static ?string $navigationIcon = 'heroicon-o-menu';

    public static function getTranslatableLocales(): array
    {
        return config('flux-catalog.languages');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->label(trans('admin.value')),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->label(trans('admin.description')),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->label(trans('admin.is_active'))
                    ->inline(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('value')
                    ->label(trans('admin.value'))
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label(trans('admin.is_active')),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListValue::route('/'),
            'create' => Pages\CreateValue::route('/create'),
            'edit' => Pages\EditValue::route('/{record}/edit'),
        ];
    }
}
