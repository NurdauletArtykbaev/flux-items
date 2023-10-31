<?php

namespace Nurdaulet\FluxItems\Filament\Resources;

use Nurdaulet\FluxItems\Filament\Resources\BannedTopSearchWordResource\Pages;
use Nurdaulet\FluxItems\Models\BannedTopSearchWord;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class BannedTopSearchWordResource extends Resource
{
    protected static ?string $model = BannedTopSearchWord::class;
    protected static ?string $modelLabel = 'Популярные запросы (запрещенные слова)';
    protected static ?string $pluralModelLabel = 'Популярные запросы (запрещенные слова)';
    protected static ?string $navigationIcon = 'heroicon-o-menu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('word')
                    ->label(trans('admin.text'))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('word')
                    ->label(trans('admin.text')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('id');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTopSearchWords::route('/'),
        ];
    }
}
