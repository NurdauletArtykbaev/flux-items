<?php

namespace Nurdaulet\FluxItems\Filament\Resources;

use Nurdaulet\FluxItems\Filament\Resources\ConditionResource\Pages;
use Nurdaulet\FluxItems\Models\Condition;
use Filament\Forms;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConditionResource extends Resource
{
    use Translatable;

    protected static ?string $model = Condition::class;

    protected static ?string $modelLabel = 'состояние';
    protected static ?string $pluralModelLabel = 'Состояния';

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    public static function getTranslatableLocales(): array
    {
        return config('flux-items.languages');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label(trans('admin.name')),
                Forms\Components\TextInput::make('description')
                    ->label(trans('admin.description')),
                Forms\Components\Toggle::make('status')
                    ->required()
                    ->label(trans('admin.status')),
            ]);
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
            'index' => Pages\ListConditions::route('/'),
            'create' => Pages\CreateCondition::route('/create'),
            'edit' => Pages\EditCondition::route('/{record}/edit'),
        ];
    }
}
