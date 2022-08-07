<?php

namespace Alfred\Workflows\ItemParam;

use Exception;

class Mod
{
    use HasAnIcon, HasParams;

    const KEY_SHIFT = 'shift';

    const KEY_FN = 'fn';

    const KEY_CTRL = 'ctrl';

    const KEY_ALT = 'alt';

    const KEY_CMD = 'cmd';

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

    public function subtitle(string $subtitle): Mod
    {
        $this->params['subtitle'] = $subtitle;

        return $this;
    }

    public function arg(string $arg): Mod
    {
        $this->params['arg'] = $arg;

        return $this;
    }

    public function valid(bool $valid): Mod
    {
        $this->params['valid'] = !!$valid;

        return $this;
    }

    /**
     * Variables can be passed out of the script filter within a variables object.
     * This is useful for two things. Firstly, these variables will be passed out of
     * the script filter's outputs when actioning a result. Secondly, any variables
     * passed out of a script will be passed back in as environment variables when the
     * script is run within the same session. This can be used for very simply
     * managing state between runs as the user types input or when the script is set
     * to re-run after an interval.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/#variables
     */
    public function variable(string $key, $value): Mod
    {
        $this->mergeParam('variables', [$key => $value]);

        return $this;
    }

    public function toArray(): array
    {
        ksort($this->params);

        return [$this->key => $this->params];
    }
}
