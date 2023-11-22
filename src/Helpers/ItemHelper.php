<?php

namespace Nurdaulet\FluxItems\Helpers;

class ItemHelper
{

    const TYPE_RENT = 1;
    const TYPE_SELL = 2;

    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 2;
    const STATUS_OUT_STOCK = 3;
    const STATUS_SOLD = 4;

    const TYPES = [
         self::TYPE_RENT => 'rent',
         self::TYPE_SELL => 'sell',
    ];

    const STATUSES = [
         self::STATUS_ACTIVE => 'active',
         self::STATUS_NOT_ACTIVE => 'not_active',
         self::STATUS_OUT_STOCK => 'out_stock',
         self::STATUS_SOLD => 'sold',
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

    public static function getPrice($item, $rentTypeId = null)
    {
        $itemType = ($item->type ?? ItemHelper::TYPE_RENT);
        $rentType = $item->rentTypes->first();

        if ($rentTypeId) {
            $rentType = $item->rentTypes->where('id', $rentTypeId)->first();
        }
        if ($itemType == ItemHelper::TYPE_SELL) {
            $price = $item->price;
            $oldPrice = $item->price;
        } else if (config('flux-items.options.is_rent_daily')) {

            $price = $rentType?->pivot?->prices?->first()?->price;
            $oldPrice = $rentType?->pivot?->prices?->first()?->price;
        } else {
            $price = $rentType?->pivot?->price;
            $oldPrice = $rentType?->pivot?->old_price;
        }
        return [$price, $oldPrice];
    }
}
