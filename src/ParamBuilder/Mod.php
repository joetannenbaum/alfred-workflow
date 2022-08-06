<?php

namespace Alfred\Workflows\ParamBuilder;

use Alfred\Workflows\ItemParam\Mod as ItemParamMod;

/**
 * @method static \Alfred\Workflows\ItemParam\Mod shift()
 * @method static \Alfred\Workflows\ItemParam\Mod fn()
 * @method static \Alfred\Workflows\ItemParam\Mod ctrl()
 * @method static \Alfred\Workflows\ItemParam\Mod alt()
 * @method static \Alfred\Workflows\ItemParam\Mod cmd()
 */

class Mod
{
    /**
     * Instantiate a new \Alfred\Workflows\IItemParam\Mod
     * instance using a specified mod key.
     *
     * @param \Alfred\Workflows\ItemParam\Mod::KEY_* $mod
     */
    public static function key($key): ItemParamMod
    {
        return new ItemParamMod($key);
    }

    public static function __callStatic(string $method, array $parameters): ItemParamMod
    {
        return new ItemParamMod($method);
    }
}
