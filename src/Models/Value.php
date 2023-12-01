<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Value extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $fillable = [
        "value",
        "description",
        "is_active",
    ];
    public $translatable = ['value'];

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_value');
    }

    public function getTextAttribute()
    {
        return $this->description . ': ' .$this->value;
    }

}
