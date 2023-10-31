<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class ReceiveMethod extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'receive_methods';
    protected $guarded = ['id'];
    public array $translatable = ['name'];

    protected $casts = [
        'is_active' => 'boolean',
        'name' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }


    public function items()
    {
        return $this->belongsToMany(Item::class, ReceiveMethodItem::class,
            'receive_method_id', 'item_id')
            ->withPivot('delivery_price');
    }

    public function name(): Attribute
    {
        $langs = config('flux-items.languages');
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => isset(json_decode($attributes['name'])?->{app()->getLocale()}) && json_decode($attributes['name'])?->{app()->getLocale()} ? json_decode($attributes['name'])?->{app()->getLocale()} : json_decode($attributes['name'])?->{$langs[0]},
        );
    }

}
