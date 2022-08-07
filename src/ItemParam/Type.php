<?php

namespace Alfred\Workflows\ItemParam;

use Exception;

class Type
{
    const TYPE_DEFAULT = 'default';

    const TYPE_FILE = 'file';

    const TYPE_FILE_SKIP_CHECK = 'file:skipcheck';

    /**
     * @param string $type see Type::TYPE_*
     * @param bool $verify_existence When used with $type Type::TYPE_FILE
     * @throws Exception if $type is invalid
     */
    public static function handle(string $type, ?bool $verify_existence): string
    {
        if (!in_array($type, [self::TYPE_DEFAULT, self::TYPE_FILE, self::TYPE_FILE_SKIP_CHECK])) {
            throw new Exception(
                'Invalid type [' . $type . '], use \Alfred\Workflows\ItemParam\Type::TYPE_*'
            );
        }

        if ($type === self::TYPE_FILE && $verify_existence === false) {
            return self::TYPE_FILE_SKIP_CHECK;
        }

        return $type;
    }
}
