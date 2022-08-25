<?php

namespace Alfred\Workflows\ParamBuilder;

use Alfred\Workflows\ItemParam\Mod as ItemParamMod;
use Exception;

/**
 * @method static ItemParamMod shift()
 * @method static ItemParamMod fn()
 * @method static ItemParamMod ctrl()
 * @method static ItemParamMod alt()
 * @method static ItemParamMod cmd()
 */

class Mod
{
    /**
     * Instantiate a new \Alfred\Workflows\ItemParam\Mod
     * instance using a specified mod key.
     *
     * @param string $key see \Alfred\Workflows\ItemParam\Mod::KEY_*
     * @throws Exception when $key is invalid
     */
    public static function key(string $key): ItemParamMod
    {
        return new ItemParamMod($key);
    }

    public static function combo(array $keys)
    {
        return new ItemParamMod($keys);
    }

    /**
     * @throws Exception when $key is invalid
     */
    public static function __callStatic(string $method, array $parameters): ItemParamMod
    {
        return new ItemParamMod($method);
    }
}
