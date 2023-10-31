<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class City extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'cities';
    protected $guarded = ['id'];


    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

   
    public function items()
    {
        return $this->belongsToMany(Item::class, ItemCity::class,
            'city_id', 'item_id');
    }

}
