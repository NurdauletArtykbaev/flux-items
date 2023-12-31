<?php


return [
    'models' => [
        'promotion_group' => \Nurdaulet\FluxItems\Models\PromotionGroup::class,
        'item' => \Nurdaulet\FluxItems\Models\Item::class,
        'favorite_item' => \Nurdaulet\FluxItems\Models\FavoriteItem::class,
        'banned_top_search_word' => \Nurdaulet\FluxItems\Models\BannedTopSearchWord::class,
        'view_history_item' => \Nurdaulet\FluxItems\Models\ViewHistoryItem::class,
        'complain_item' => \Nurdaulet\FluxItems\Models\ComplaintItem::class,
        'rent_type' => \Nurdaulet\FluxItems\Models\RentType::class,
        'condition' => \Nurdaulet\FluxItems\Models\Condition::class,
        'catalog' => \Nurdaulet\FluxItems\Models\Catalog::class,
        'user' => \Nurdaulet\FluxItems\Models\User::class,
        'receive_method' => \Nurdaulet\FluxItems\Models\ReceiveMethod::class,
        'temprory_image' => \Nurdaulet\FluxItems\Models\TemproryImage::class,
        'image_item' => \Nurdaulet\FluxItems\Models\ImageItem::class,
        'return_method' => \Nurdaulet\FluxItems\Models\ReturnMethod::class,
        'protect_method' => \Nurdaulet\FluxItems\Models\ProtectMethod::class,
        'rent_type_item' => \Nurdaulet\FluxItems\Models\RentTypeItem::class,
        'rent_item_price' => \Nurdaulet\FluxItems\Models\RentItemPrice::class,
        'cart' => \Nurdaulet\FluxItems\Models\Cart::class,
        'value' => \Nurdaulet\FluxItems\Models\Value::class,
        'property' => \Nurdaulet\FluxItems\Models\Property::class,
        'cart_item' => \Nurdaulet\FluxItems\Models\CartItem::class,
        'user_address' => \Nurdaulet\FluxItems\Models\UserAddress::class,
    ],
    'languages' => [
        'ru', 'en', 'kk'
    ],
    'options' => [
        'is_rent_daily' => false,
        'site_items_base_url' => "https://",
        'use_filament_admin_panel' => true,
        'use_list_items_count' => false,
        'is_enabled_item_property' => true,
        'cache_expiration' => 269746,
        'use_roles' => false
    ],
];
