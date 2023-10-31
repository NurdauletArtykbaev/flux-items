<?php

namespace App\Filament\Resources\ItemResource\RelationManagers;

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

class RentalDaysRelationManager extends RelationManager
{
    protected static string $relationship = 'rentalDays';

    protected static ?string $recordTitleAttribute = 'text';

    protected static ?string $modelLabel = 'период';
    protected static ?string $pluralModelLabel = 'Период аренды';


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('days')
                    ->label(trans('admin.days')),
                Tables\Columns\TextColumn::make('type')
                    ->label(trans('admin.type')),
                Tables\Columns\TextColumn::make('price')
                    ->label(trans('admin.price')),

            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn(AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->searchable()
                            ->helperText(function () {
                                $flattened = RentalDayHelper::getTypeOptions();
                                array_walk($flattened, function (&$value, $key) {
                                    $value = "{$value} - {$key} <br>";
                                });
                                return 'Для поиска: <br>' . implode('', $flattened);
                            })
                            ->getSearchResultsUsing(fn(string $search) => RentalDay::whereRaw("concat(type, ' ', 'days') like '%" . $search . "%'")
                                ->orWhere('type', 'like', "%$search%")
                                ->orderBy('days','asc')
                                ->limit(50)->selectRaw("id,   concat(days, ' ',type) as info")
                                ->pluck('info', 'id'))
                            ->optionsLimit(1000)
                            ->preload()
                            ->label(trans('admin.rental_days')),
                        Forms\Components\TextInput::make('price')
                            ->label(trans('admin.price'))
                            ->required(),
                    ])
            ])
            ->actions([
                // ...
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                // ...
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
