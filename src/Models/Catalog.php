<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Catalog extends Model
{
    use HasFactory, HasTranslations, HasRecursiveRelationships, SoftDeletes;

    protected $guarded = ['id'];
    protected $casts = [
        'name' => 'array',
        'seo_title' => 'array',
        'seo_text' => 'array',
    ];

    public $translatable = ['name', 'seo_title', 'seo_text'];


    public function getDepthName()
    {
        return 'level';
    }

    public function getParentKeyName()
    {
        return 'parent_id';
    }

    public function parent()
    {
        return $this->belongsTo(\Nurdaulet\FluxItems\Models\Catalog::class, 'parent_id');
    }

    public function childs()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
    public function promotionGroup()
    {
        return $this->belongsTo(self::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'catalog_item', 'catalog_id', 'item_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

}
