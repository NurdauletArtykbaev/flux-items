<?php

namespace Nurdaulet\FluxItems\Filament\Resources;

use Nurdaulet\FluxCatalog\Repositories\CatalogRepository;
use Nurdaulet\FluxItems\Facades\StringFormatter;
use Nurdaulet\FluxItems\Filament\Resources\ItemResource\Pages;
use Nurdaulet\FluxItems\Filament\Resources\ItemResource\RelationManagers;
use Nurdaulet\FluxItems\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Nurdaulet\FluxItems\Models\Item;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;
    protected static ?string $modelLabel = 'объявление';
    protected static ?string $pluralModelLabel = 'Объявления';

    protected static ?string $navigationIcon = 'heroicon-o-cash';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('condition_id')
                    ->relationship('condition', 'name')
                    ->translateLabel()
                    ->preload()
                    ->label(trans('admin.condition')),
                Forms\Components\Select::make('user_id')
                    ->label(trans('admin.user'))
                    ->searchable()
                    ->getSearchResultsUsing(fn(string $search) => User::whereRaw("concat(name, ' ', 'surname', ' ', 'company_name') like '%" . $search . "%'")
                        ->when(StringFormatter::onlyDigits($search), function ($query) use ($search) {
                            $query->orWhere('phone', 'like', "%" . StringFormatter::onlyDigits($search) . "%");
                        })
                        ->limit(50)->selectRaw("id,   concat(name, ' ',surname, ' | ' , phone) as info")
                        ->pluck('info', 'id'))
                    ->getOptionLabelUsing(function ($value) {
                        $user = User::find($value);
                        return $user?->name . ' ' . $user?->surname . ' | ' . $user->phone;
                    }),
                Forms\Components\Select::make('type')
                    ->options(\Nurdaulet\FluxItems\Helpers\ItemHelper::getTypes()),
//                Forms\Components\Select::make('city_id')
//                    ->relationship('city', 'name')
//                    ->preload()
//                    ->label(trans('admin.city')),
                Select::make('cities')
                    ->label(trans('admin.city'))
                    ->multiple()
                    ->preload()
                    ->relationship('cities', 'name', fn(Builder $query) => $query->active()),
                Select::make('catalogs')
                    ->multiple()
                    ->relationship('catalogs', 'name')
                    ->searchable()
                    ->optionsLimit(1000)
                    ->options((new CatalogRepository())->getCatalogForAdminPanel())
                    ->preload()
                    ->label(trans('admin.catalogs')),
                Forms\Components\CheckboxList::make('returnMethods')
                    ->translateLabel()
                    ->relationship('returnMethods', 'name')
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->name)
                    ->label(trans('admin.return_methods')),
                Forms\Components\CheckboxList::make('protectMethods')
                    ->relationship('protectMethods', 'name')
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->name)
                    ->translateLabel()
                    ->label(trans('admin.protect_methods')),
                Forms\Components\TextInput::make('name')
                    ->maxLength(255)
                    ->required()
                    ->label(trans('admin.name')),
                Forms\Components\Textarea::make('description')
                    ->label(trans('admin.description')),
                Forms\Components\Textarea::make('address_name')
                    ->maxLength(65535)
                    ->label(trans('admin.address')),
                Forms\Components\TextInput::make('lat')
                    ->maxLength(255)
                    ->label(trans('admin.lat')),
                Forms\Components\TextInput::make('lng')
                    ->maxLength(255)
                    ->label(trans('admin.lng')),
                Forms\Components\Toggle::make('status')
                    ->required()
                    ->label(trans('admin.is_active')),
                Forms\Components\Toggle::make('is_required_deposit')
                    ->label(trans('admin.is_required_deposit')),
                Forms\Components\Toggle::make('is_busy')
                    ->label(trans('admin.is_busy')),
                Forms\Components\Toggle::make('is_hit')
                    ->label(trans('admin.hit')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.phone')
                    ->sortable()
                    ->searchable()
                    ->label(trans('admin.user')),
                Tables\Columns\TextColumn::make('cities.name')->label(trans('admin.cities')),

                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label(trans('admin.name')),
                Tables\Columns\BooleanColumn::make('status')->label(trans('admin.status')),
                Tables\Columns\BooleanColumn::make('is_hit')->label(trans('admin.hit')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->label(trans('admin.created_at')),
            ])
            ->filters([
                Filter::make('is_hit')->label(trans('admin.hit'))
                    ->query(fn(Builder $query): Builder => $query->where('is_hit', true)),
                Filter::make('is_not_prices')->label(trans('admin.is_not_prices'))
                    ->query(fn(Builder $query): Builder => $query->doesntHave('rentTypes')->orDoesntHave('allPrices')),
                Filter::make('is_not_images')->label(trans('admin.is_not_images'))
                    ->query(fn(Builder $query): Builder => $query->doesntHave('images')),
                SelectFilter::make('status')
                    ->label(trans('admin.status'))
                    ->options([
                            0 => 'Не активный',
                            1 => 'Активный'
                        ]
                    ),
                SelectFilter::make('city_id')
                    ->label(trans('admin.city'))
                    ->relationship('cities', 'name', fn(Builder $query) => $query->active()),
                SelectFilter::make('user_id')
                    ->label(trans('admin.user'))
//                    ->relationship('user', 'phone'),
//                        ->preload()
                    ->options(User::orderBy('name')
                        ->whereNotNull('name')->get()
                        ->pluck('full_name_with_phone', 'id')
                        ->toArray()
                    ),

//                SelectFilter::make('user_id')
//                    ->label(trans('admin.user'))
//                    ->searchable()
//
//                    ->getSearchResultsUsing(fn(string $search) => User::whereRaw("concat(name, ' ', 'surname', ' ', 'company_name') like '%" . $search . "%'")
//                        ->when(StringFormatter::onlyDigits($search), function ($query) use ($search) {
//                            $query->orWhere('phone', 'like', "%" . StringFormatter::onlyDigits($search) . "%");
//
//                        })
//                        ->limit(50)->selectRaw("id,   concat(name, ' ',surname, ' | ' , phone) as info")
//                        ->pluck('info', 'id'))
//                    ->getOptionLabelUsing(function ($value) {
//                        $user = User::find($value);
//                        return $user?->name . ' ' . $user?->surname . ' | ' . $user->phone;
//                   phone }),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('с даты'),
                        Forms\Components\DatePicker::make('created_until')->label('до даты'),
                    ])->query(function (Builder $query, array $data): Builder {

                        return $query
                            ->when(isset($data['created_from']), function ($query) use ($data) {
                                return $query->whereDate('created_at', '>=', $data['created_from']);
                            })
                            ->when(isset($data['created_until']), function ($query) use ($data) {
                                return $query->whereDate('created_at', '<=', $data['created_until']);
                            });
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('На_сайте')
                    ->url(fn(Item $record): string => url(env('SITE_URL') ."/product/$record->slug/"))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),

                ReplicateAction::make()->afterReplicaSaved(function (Model $replica, Item $record) use ($table): void {
                    $item = Item::where('id', $record->id)->withAll()->first();

                    foreach ($item->images as $image) {
                        $replica->images()->save($image);
                    }

                    foreach ($item->catalogs as $catalog) {
                        $replica->catalogs()->attach($catalog);
                        $replica->push();
                    }
                    foreach ($item->receiveMethods as $receiveMethod) {
                        DB::table('receive_method_item')->insert([
                            'receive_method_id' => $receiveMethod->id,
                            'item_id' => $replica->id,
                            'delivery_price' => $receiveMethod->pivot->price ?? 0,
                        ]);
                    }
                    foreach ($item->returnMethods as $returnMethod) {
                        $replica->returnMethods()->attach($returnMethod);
                    }
                    foreach ($item->protectMethods as $protectMethod) {
                        $replica->protectMethods()->attach($protectMethod);
                    }
//                    foreach ($item->intercities as $intercity) {
//                        $replica->intercities()->save($intercity);
//                    }
                    $replica->push();
                })
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'DESC');
    }


    public static function getRelations(): array
    {
        if (config('flux-items.options.is_rent_daily')) {
            return [
                RelationManagers\ImagesRelationManager::class,
                RelationManagers\ReceiveMethodsRelationManager::class,
                RelationManagers\RentItemPricesRelationManager::class,
            ];
        }
        return [
            RelationManagers\ImagesRelationManager::class,
            RelationManagers\ReceiveMethodsRelationManager::class,
            RelationManagers\RentalDayTypesRelationManager::class,
        ];
    }
}
