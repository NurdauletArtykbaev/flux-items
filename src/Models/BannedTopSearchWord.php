<?php

namespace Nurdaulet\FluxItems\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannedTopSearchWord extends Model
{
    use HasFactory;
    protected $fillable = [
        'word'
    ];
}
