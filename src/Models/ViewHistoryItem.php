<?php

namespace Nurdaulet\FluxITems\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ViewHistoryItem extends Pivot
{
    protected $guarded = ['id'];
}
