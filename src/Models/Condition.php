<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Condition extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;
    protected $guarded = ['id'];
    public array $translatable = ['name', 'description'];


    public function Items()
    {
        return $this->hasMany(Item::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
