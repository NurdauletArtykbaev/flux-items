<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RentTypeItem extends Pivot
{
    use HasFactory;
    public $incrementing = true;
    use \Awobaz\Compoships\Compoships;
    protected $fillable = [
        'rent_type_id',
        'item_id',
        'price',
        'old_price',

    ];

    public function prices()
    {
        return $this->hasMany(RentItemPrice::class,['rent_type_id','item_id'],['rent_type_id','item_id'])->orderBy('value');
    }
}
