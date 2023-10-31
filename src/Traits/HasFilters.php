<?php

namespace Nurdaulet\FluxItems\Traits;

use Nurdaulet\FluxItems\Filters\ModelFilter;
use Illuminate\Database\Eloquent\Builder;

trait HasFilters
{
    public function scopeApplyFilters(Builder $builder, ModelFilter $modelFilter, array $filters): Builder
    {
        return $modelFilter->apply($builder, $filters);
    }
}
