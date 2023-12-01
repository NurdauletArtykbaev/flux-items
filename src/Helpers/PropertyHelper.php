<?php

namespace Nurdaulet\FluxItems\Helpers;

class PropertyHelper
{

    const DEFAULT_INPUT_TYPE = 1;
    const INPUT_TYPES = [
        1 => "select",
        2 => "radio",
        3 => "checkbox",
        4 => "text",
        5 => "color",
    ];

    public static function getTypes()
    {
        $types = [];
        foreach (self::INPUT_TYPES as  $key => $value) {
            $types[$key] = trans("admin.$value");
        }
        return $types;
    }
}
