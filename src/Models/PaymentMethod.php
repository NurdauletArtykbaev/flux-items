<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;
    protected $table = 'payment_methods';
    protected $guarded = ['id'];
    protected $appends = ['image_url'];
    public $translatable = ['name'];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? (config('filesystems.disks.s3.url').'/'.$this->image) : null;
    }

    public function name(): Attribute
    {
        $langs = config('flux-orders.languages');
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => isset(json_decode($attributes['name'])?->{app()->getLocale()}) && json_decode($attributes['name'])?->{app()->getLocale()} ? json_decode($attributes['name'])?->{app()->getLocale()} : json_decode($attributes['name'])?->{$langs[0]},
        );
    }
}
