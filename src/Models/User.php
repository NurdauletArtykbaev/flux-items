<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'is_identified' => 'boolean'
    ];

    public function favorites()
    {
        return $this->hasMany(FavoriteItem::class, 'user_id');
    }
    public function ratings()
    {
        return $this->hasMany(UserRating::class, 'receiver_id');
    }

    public function getFullNameWithPhoneAttribute()
    {
        return $this->name . ' ' . $this->surname . '| ' . $this->phone;
    }
}
