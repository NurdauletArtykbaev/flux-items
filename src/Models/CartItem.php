<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Pivot
{
    use HasFactory, SoftDeletes;
    use \Awobaz\Compoships\Compoships;
    protected $fillable = [
        'item_id',
        'quantity',
        'cart_id',
        'user_address_id',
        'receive_method_id',
        'fields',
        'rent_value',
        'rent_type_id',
    ];
    public function receiveMethod()
    {
        return $this->belongsTo(ReceiveMethod::class);
    }

    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class);
    }

}
