<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nurdaulet\FluxBase\Traits\Reviewable;

class User extends Model
{
    use HasFactory, Reviewable, SoftDeletes;

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

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? (config('filesystems.disks.s3.url') . '/' . $this->avatar) : null;
    }
}
