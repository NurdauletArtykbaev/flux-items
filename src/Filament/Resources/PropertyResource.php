<?php

namespace Nurdaulet\FluxItems\Filament\Resources;

use Nurdaulet\FluxItems\Filament\Resources\PropertyResource\Pages;
use Filament\Forms;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Nurdaulet\FluxItems\Filament\Resources\PropertyResource\RelationManagers\ValueRelationManager;
use Nurdaulet\FluxItems\Models\Property;

class PropertyResource extends Resource
{
    use Translatable;

    protected static ?string $model = Property::class;

    protected static ?string $modelLabel = 'Свойства';
    protected static ?string $pluralModelLabel = 'Свойства';

    protected static ?string $navigationIcon = 'heroicon-o-menu';

    public static function getTranslatableLocales(): array
    {
        return config('flux-catalog.languages');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label(trans('admin.name')),
                Forms\Components\Textarea::make('description')
                    ->label(trans('admin.description'))
                ->rows(10)->cols(10),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->label(trans('admin.is_active'))
                    ->inline(),
//                Forms\Components\Repeater::make('values')
//                    ->relationship()
//                    ->schema([
//                        Forms\Components\TextInput::make('name')
//                            ->label(trans('admin.name')),
//                        Forms\Components\Toggle::make('is_active')
//                            ->required()
//                            ->label(trans('admin.is_active'))
//                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(trans('admin.name'))->searchable(),
                Tables\Columns\TextColumn::make('description')->label(trans('admin.description'))->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')->label(trans('admin.is_active')),
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

    public static function getRelations(): array
    {
        return [
            ValueRelationManager::class
//            PropertyValueRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperty::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
