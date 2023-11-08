<?php

namespace Nurdaulet\FluxItems\Helpers;

class ItemHelper
{

    const TYPE_RENT = 1;
    const TYPE_SALE = 2;

    const TYPES = [
         self::TYPE_RENT => 'rent',
         self::TYPE_SALE => 'sale',
    ];

    public static function getTypes()
    {
        return array_map(fn($key, $value) => trans("admin.{$value}"), array_keys(self::TYPES), self::TYPES);
    }

    public static function getPriceRelation()
    {
        if (config('flux-items.options.is_rent_daily')) {
            return 'rentTypes.pivot.prices';
        }
        return 'rentTypes';
    }

}
