<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemProperty extends Pivot
{
    public function value()
    {
        return $this->belongsTo(Value::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
