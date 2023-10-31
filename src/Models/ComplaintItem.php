<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplaintItem extends Pivot
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function compReason()
    {
        return $this->belongsTo(ComplaintReason::class, 'complaint_reason_id');
    }
}
