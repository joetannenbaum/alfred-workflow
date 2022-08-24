<?php

namespace Alfred\Workflows\ItemParam;

use Exception;

class Mod
{
    use HasAnIcon;
    use HasParams;
    use HasValidity;
    use HasVariables {
        variable as baseVariable;
        variables as baseVariables;
    }
    use HasArguments;
    use HasSubtitle;

    public const KEY_SHIFT = 'shift';

    public const KEY_FN = 'fn';

    public const KEY_CTRL = 'ctrl';

    public const KEY_ALT = 'alt';

    public const KEY_CMD = 'cmd';

    /**
     * @var array<string>
     */
    protected array $keys;

    /**
     * @param array|string $key see Mod::KEY_*
     * @throws Exception when $key is invalid
     */
    public function __construct($key)
    {
        if (is_array($key)) {
            foreach ($key as $k) {
                $this->validateKey($k);
            }

            $this->keys = $key;
        } else {
            $this->validateKey($key);

            $this->keys = [$key];
        }
    }

    /**
     * @throws Exception when $key is invalid
     */
    protected function validateKey(string $key)
    {
        if (!in_array($key, [self::KEY_SHIFT, self::KEY_FN, self::KEY_CTRL, self::KEY_ALT, self::KEY_CMD])) {
            throw new Exception('Invalid mod [' . $key . '], use \Alfred\Workflows\ItemParam\Mod::KEY_*');
        }
    }

    public function variable($key, $value = null): self
    {
        if ($key === null) {
            $this->params['variables'] = [];

            return $this;
        }

        return $this->baseVariable($key, $value);
    }

    public function variables(array $variables): self
    {
        if (empty($variables)) {
            $this->params['variables'] = [];

            return $this;
        }

        return $this->baseVariables($variables);
    }

    public function toArray(): array
    {
        ksort($this->params);

        $key = implode('+', $this->keys);

        return [$key => $this->params];
    }
}
