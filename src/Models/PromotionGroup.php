<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nurdaulet\FluxItems\Models\Catalog;
use Spatie\Translatable\HasTranslations;

class PromotionGroup extends Model
{
    use HasFactory, SoftDeletes,HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'banner_catalog_id',
        'banner_title',
        'banner_position_left',
        'banner_bg_color',
        'banner_image',
        'sort',
        'is_active'
    ];
    public $translatable = ['name', 'banner_title'];

    protected $casts = [
        'name' => 'array',
        'banner_title' => 'array',

        'is_active' => 'boolean'
    ];

    public function catalog()
    {
        return $this->belongsTo(Catalog::class, 'banner_catalog_id', 'id');
    }

    public function catalogs()
    {
        return $this->belongsToMany(Catalog::class, 'promotion_group_catalog', 'promotion_group_id', 'catalog_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
