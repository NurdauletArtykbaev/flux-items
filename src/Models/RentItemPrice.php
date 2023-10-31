<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nurdaulet\FluxItems\Models\Item;

class RentItemPrice extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;
    protected $fillable = [
        'item_id',
        'rent_type_id',
        'price',
        'weekend_price',
        'value',
        'from',
        'to',

    ];

    public function rentType()
    {
        return $this->belongsTo(RentType::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }
}
