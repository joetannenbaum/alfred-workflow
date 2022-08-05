<?php

namespace Alfred\Workflows\ItemParam;

class Mod
{
    const MOD_SHIFT = 'shift';

    const MOD_FN = 'fn';

    const MOD_CTRL = 'ctrl';

    const MOD_ALT = 'alt';

    const MOD_CMD = 'cmd';

    /**
     * @param \Alfred\Workflows\ItemParam\Mod::MOD_* $mod
     * @param string $subtitle
     * @param string $arg
     * @param bool $valid
     * @throws \Exception when $type is invalid
     * @return array
     */
    public static function handle($mod, $subtitle, $arg, $valid)
    {
        if (!in_array($mod, [self::MOD_SHIFT, self::MOD_FN, self::MOD_CTRL, self::MOD_ALT, self::MOD_CMD])) {
            throw new \Exception('Invalid mod [' . $mod . '], use \Alfred\Workflows\ItemParam\Mod::MOD_*');
        }

        return [$mod => compact('subtitle', 'arg', 'valid')];
    }
}
