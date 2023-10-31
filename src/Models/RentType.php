<?php

namespace Nurdaulet\FluxItems\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class RentType extends Model
{
    use HasFactory, SoftDeletes,HasTranslations, EagerLoadPivotTrait;
    protected $guarded = ['id'];
    public array $translatable = ['name'];

    public function products()
    {
        return $this->belongsToMany(Item::class,RentTypeItem::class,'rent_type_id','id')

            ->withPivot('item_id','rent_type_id')
            ->using(RentTypeItem::class);
    }


    public function prices()
    {
        return $this->hasMany(RentProductPrice::class,'rent_type_id');
    }
}
