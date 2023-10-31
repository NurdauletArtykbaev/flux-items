<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class ComplaintReason extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $guarded = ['id'];
    public array $translatable = ['name'];
    protected $casts = [
        'is_for_user' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
