<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ItemResource\RelationManagers;

use Nurdaulet\FluxItems\Models\RentType;
use Nurdaulet\FluxItems\Repositories\ItemRepository;
use Nurdaulet\FluxItems\Services\ItemService;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Contracts\HasRelationshipTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class RentItemPricesRelationManager extends RelationManager
{
    protected static string $relationship = 'rentDailyAllPrices';
    protected static ?string $modelLabel = 'Цены';
    protected static ?string $pluralModelLabel = 'Цены';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rent_type_id')
                    ->relationship('rentType', 'name')
                    ->preload()
                    ->required()
                    ->label(trans('admin.rent_type')),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->label(trans('admin.rent_value')),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->label(trans('admin.price')),
                Forms\Components\TextInput::make('weekend_price')
                    ->label(trans('admin.weekend_price')),
                Forms\Components\TextInput::make('to')
                    ->label(trans('admin.rent_type_price_time_to')),
                Forms\Components\TextInput::make('from')
                    ->label(trans('admin.rent_type_price_time_from')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rentType.name')
                    ->label(trans('admin.rent_type')),
                Tables\Columns\TextColumn::make('value')
                    ->label(trans('admin.value')),
                Tables\Columns\TextColumn::make('price')
                    ->label(trans('admin.price')),
                Tables\Columns\TextColumn::make('weekend_price')
                    ->label(trans('admin.weekend_price')),
            ])
            ->filters([
                Filter::make('value')
                    ->form([
                        Forms\Components\TextInput::make('value')->label(trans('admin.value')),
                    ])->query(function (Builder $query, array $data): Builder {

                        return $query
                            ->when(isset($data['value']), function ($query) use ($data) {
                                return $query->where('value', 'like', "%" . $data['value'] . "%");
                            });
                    }),
                SelectFilter::make('rent_type_id')
                    ->relationship('rentType', 'name'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (HasRelationshipTable $livewire, array $data) {
                        if ($livewire->getRelationship()->getParent()->rentDailyAllPrices()
                                ->where('rent_item_prices.rent_type_id', $data['rent_type_id'])->count() < 2) {
                            $rentType = RentType::findOrFail($data['rent_type_id']);
                            $item = $livewire->getRelationship()->getParent();
                            $prices = $livewire->getRelationship()->getParent()
                                ->rentDailyAllPrices()
                                ->where('rent_item_prices.rent_type_id', $data['rent_type_id'])->get()->toArray();
                            $itemService = new  ItemService(new ItemRepository());
                            $itemService->recalculateIsDaidyPriceAndSave($item->id, $prices, $rentType);
                        }
                    })


            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (HasRelationshipTable $livewire, array $data): array {
                        if (!$livewire->getRelationship()->getParent()->rentTypes()->where('rent_types.id', $data['rent_type_id'])->exists()) {
                            $livewire->getRelationship()->getParent()->rentTypes()->attach($data['rent_type_id']);
                        }
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make()->before(function (DeleteAction $action, RelationManager $livewire) {

                    if (!$livewire->getRelationship()->getParent()->rentDailyAllPrices()
                        ->where('rent_item_prices.rent_type_id', $action->getRecord()->rent_type_id)
                        ->where('rent_item_prices.id', '<>', $action->getRecord()->id)
                        ->exists()) {
                        $livewire->getRelationship()->getParent()->rentTypes()->detach($action->getRecord()->rent_type_id);
                    }
                }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
