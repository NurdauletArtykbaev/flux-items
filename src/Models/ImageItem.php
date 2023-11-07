<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageItem extends Pivot
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $appends = ['full_url', 'webp_full_url'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id')->withTrashed();
    }

    public function getFullUrlAttribute()
    {
        return config('filesystems.disks.s3.url').'/'.$this->image;
    }

    public function getWebpFullUrlAttribute()
    {
        return $this->webp ?  config('filesystems.disks.s3.url').'/'.$this->webp : null;
    }
}
