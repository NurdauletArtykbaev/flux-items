<?php

namespace Nurdaulet\FluxItems\Models;

use Nurdaulet\FluxItems\Facades\TextConverter;
use Nurdaulet\FluxItems\Traits\HasFilters;
use Nurdaulet\FluxBase\Traits\Reviewable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


class Item extends Model
{
    use HasFactory, Reviewable, Searchable, SoftDeletes, HasFilters;

    protected $table = 'items';
    protected $guarded = ['id'];

    public function toSearchableArray()
    {
        $this->loadMissing(['catalogs', 'cities', 'user:id,company_name,name,surname']);
        $companyName = $this->user?->company_name;
        $companyName = $companyName ?: $this->user->name . ' ' . $this->user->surname;
        return [
            'objectID' => $this->id,
            'city_ids' => $this->cities->pluck('id')->toArray(),
            'catalog_name' => $this->catalogs?->first()?->name,
            'keyboard_trans' => TextConverter::convertToTranslator($this->name),
            'wrong_layout' => Str::slug($this->name),
            'name' => $this->name,
            'catalog_id' => $this->catalogs?->first()?->id,
            'company_name' => $companyName
        ];
    }

    protected $supportedRelations = [
        'images',
        'catalogs',
        'rentTypes.pivot.prices',
        'receiveMethods',
        'returnMethods',
        'protectMethods',
    ];

    protected $casts = [
        'is_busy' => 'boolean',
        'is_required_deposit' => 'boolean'
    ];

    public function searchableAs()
    {
        return config('services.algolia.index');
    }

    public function getScoutKey()
    {
        return $this->id;
    }

    public function getScoutKeyName(): mixed
    {
        return 'id';
    }

    public function searchIndexShouldBeUpdated()
    {
        return false;
    }

    public function description(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['description'] ?? "К сожалению, у нас временно отсутствует описание данного товара, но не переживайте - наши арендодатели всегда готовы предоставить вам всю необходимую информацию и ответить на любые ваши вопросы.

Если вы хотите узнать больше о данном товаре, просто свяжитесь с арендодателем данного товара или обратитесь к нашей службе поддержки клиентов по телефону или по электронной почте - мы всегда рады помочь вам.

Мы ценим каждого нашего клиента и всегда стремимся предоставлять лучший сервис и высококачественные товары. Надеемся, что вы останетесь довольны нашими услугами и выберете наш сервис снова и снова.",
        );
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class, 'condition_id');
    }

    public function favorites()
    {
        return $this->hasMany(FavoriteItem::class, 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function images()
    {
        return $this->hasMany(ImageItem::class);
    }

    public function catalogs()
    {
        return $this->belongsToMany(Catalog::class, 'catalog_item', 'item_id', 'catalog_id');
    }

    public function receiveMethods()
    {
        return $this->belongsToMany(ReceiveMethod::class, ReceiveMethodItem::class,
            'item_id', 'receive_method_id')->withPivot('delivery_price');
    }

    public function returnMethods()
    {
        return $this->belongsToMany(ReturnMethod::class, 'return_method_item', 'item_id', 'return_method_id');
    }

    public function protectMethods()
    {
        return $this->belongsToMany(ProtectMethod::class, 'protect_method_item', 'item_id', 'protect_method_id');
    }

    public function cities()
    {
        return $this->belongsToMany(City::class, ItemCity::class, 'item_id', 'city_id');
    }

    public function viewHistory()
    {
        return $this->hasOne(ViewHistoryItem::class, 'item_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'ad_id');
    }

//    public function store()
//    {
//        return $this->belongsTo(Store::class, 'store_id');
//    }
    /** start Testing relations*/
//    public

    public function rentTypes()
    {
        return $this->belongsToMany(RentType::class, RentTypeItem::class, 'item_id', 'rent_type_id')
            ->withPivot('item_id', 'rent_type_id');
    }

    public function rentTypePrices()
    {
        return $this->belongsToMany(RentType::class, RentTypeItem::class, 'item_id', 'rent_type_id');
    }

    public function allPrices()
    {
        return $this->hasMany(RentItemPrice::class, 'item_id', 'id');
    }

    /** end Testing relations*/

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeIsNotActive($query)
    {
        return $query->where('status', 0);
    }

    public function scopeHit($query)
    {
        return $query->where('is_hit', 1);
    }

    public function scopeWithAll($query)
    {
        return $query->with($this->supportedRelations);
    }
}
