<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property boolean $is_main
 *
 * @property-read User $user
 */
class UserAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'lng' => 'string',
        'lat' => 'string',
        'is_type_store' => 'boolean',
        'is_main' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }


    public function setIsTypeStoreAttribute($is_type_store)
    {
        $this->attributes['is_type_store'] = is_null($is_type_store) ? false : $is_type_store;
    }
}
