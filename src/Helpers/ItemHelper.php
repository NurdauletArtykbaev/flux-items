<?php

namespace Nurdaulet\FluxItems\Helpers;

class ItemHelper
{

    const TYPE_RENT = 1;
    const TYPE_SELL = 2;

    const TYPES = [
         self::TYPE_RENT => 'rent',
         self::TYPE_SELL => 'sell',
    ];

    public static function getTypes()
    {
        return  [
            self::TYPE_RENT => trans("admin." . self::TYPES[self::TYPE_RENT]),
            self::TYPE_SELL => trans("admin." . self::TYPES[self::TYPE_SELL]),
        ];
    }

    public static function getPriceRelation()
    {
        if (config('flux-items.options.is_rent_daily')) {
            return 'rentTypes.pivot.prices';
        }
        return 'rentTypes';
    }

}
