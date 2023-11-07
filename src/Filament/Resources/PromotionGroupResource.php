<?php

namespace Nurdaulet\FluxItems\Filament\Resources;

use Nurdaulet\FluxItems\Filament\Resources\PromotionGroupResource\Pages;
use Nurdaulet\FluxItems\Models\Condition;
use Filament\Forms;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Nurdaulet\FluxItems\Models\PromotionGroup;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class PromotionGroupResource extends Resource
{
    use Translatable;

    protected static ?string $model = PromotionGroup::class;

    protected static ?string $modelLabel = 'Коллекции';
    protected static ?string $pluralModelLabel = 'Коллекция';

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    public static function getTranslatableLocales(): array
    {
        return config('flux-items.languages');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                        Section::make('Основная информация')
                           ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label(trans('admin.name')),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->label(trans('admin.key')),

                                Forms\Components\Select::make('catalogs')
                                    ->label(trans('admin.catalogs'))
                                    ->multiple()
                                    ->preload()
                                    ->relationship('catalogs', 'name', fn(Builder $query) => $query->active()),
                                Forms\Components\Toggle::make('is_active')
                                    ->required()
                                    ->label(trans('admin.status')),
                            ])  ->columns(2),
                        Section::make('Информация баннера')
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('banner_title')
                                    ->maxLength(255)
                                    ->label(trans('admin.text')),
                                Forms\Components\Select::make('banner_catalog_id')
                                    ->relationship('catalog','name')
                                    ->label(trans('admin.catalog')),
                                Forms\Components\FileUpload::make('banner_image')
                                    ->image()
                                    ->disk('s3')
                                    ->directory('banners')
                                    ->label(trans('admin.image')),
                                Forms\Components\ColorPicker::make('banner_bg_color')
                                    ->label(trans('admin.bg_color')),

                                Forms\Components\Toggle::make('banner_position_left')
                                    ->default(false)
                                    ->label(trans('admin.banner_position_left')),
                            ])

                    ]) ;

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(trans('admin.name')),
                Tables\Columns\TextColumn::make('description')->label(trans('admin.description')),
                Tables\Columns\BooleanColumn::make('status')->label(trans('admin.status')),
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
            ->reorderable('sort')
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
            'index' => Pages\ListPromotionGroups::route('/'),
            'create' => Pages\CreatePromotionGroup::route('/create'),
            'edit' => Pages\EditPromotionGroup::route('/{record}/edit'),
        ];
    }
}
