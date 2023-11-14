<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'item_id',
        'quantity',
        'user_id',
        'fields',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

}
