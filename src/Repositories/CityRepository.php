<?php

namespace Nurdaulet\FluxItems\Repositories;


class CityRepository
{
    public function get()
    {
        return config('flux-base.models.city')::select('id', 'name', 'slug', 'is_active', 'lat','lng')->active()->get();
    }
}
