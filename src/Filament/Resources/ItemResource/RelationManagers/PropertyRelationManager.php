<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ItemResource\RelationManagers;


use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Nurdaulet\FluxItems\Models\Value;

class PropertyRelationManager extends RelationManager
{

    protected static string $relationship = 'properties';
    protected static ?string $modelLabel = 'Характеристика';
    protected static ?string $pluralModelLabel = 'Характеристика';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('value')
                    ->label(trans('admin.value')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('admin.name')),
                Tables\Columns\SelectColumn::make('value_id')
                    ->disabled()
                    ->options(Value::get()->pluck('value','id')->toArray())
                    ->label(trans('admin.value')),
                Tables\Columns\TextColumn::make('custom_value')
                    ->label(trans('admin.custom_value')),
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
                            ->reactive()
                            ->label(trans('admin.property')),
                        Forms\Components\Select::make('value_id')
                            ->options(function (callable $get) {
                                $values = Value::whereHas('properties' , fn($query) => $query->where('properties.id',$get('recordId') ))->get();
                                if ($values->isEmpty()){
                                 return [];
                                }
                                return  $values->pluck('value', 'id')->toArray();
                            })
                            ->label(trans('admin.value')),
                        Forms\Components\TextInput::make('custom_value')
                            ->label(trans('admin.custom_value')),
                    ])
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
