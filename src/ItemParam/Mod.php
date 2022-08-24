<?php

namespace Alfred\Workflows\ItemParam;

use Exception;

class Mod
{
    use HasAnIcon;
    use HasParams;
    use HasValidity;
    use HasVariables;
    use HasArguments;
    use HasSubtitle;

    public const KEY_SHIFT = 'shift';

    public const KEY_FN = 'fn';

    public const KEY_CTRL = 'ctrl';

    public const KEY_ALT = 'alt';

    public const KEY_CMD = 'cmd';

    /**
     * @var string see Mod::KEY_*
     */
    protected string $key;

    /**
     * @param string $key see Mod::KEY_*
     * @throws Exception when $type is invalid
     */
    public function __construct(string $key)
    {
        if (!in_array($key, [self::KEY_SHIFT, self::KEY_FN, self::KEY_CTRL, self::KEY_ALT, self::KEY_CMD])) {
            throw new Exception('Invalid mod [' . $key . '], use \Alfred\Workflows\ItemParam\Mod::KEY_*');
        }

        $this->key = $key;
    }

    public function toArray(): array
    {
        ksort($this->params);

        return [$this->key => $this->params];
    }
}
