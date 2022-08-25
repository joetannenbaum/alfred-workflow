<?php

namespace Alfred\Workflows\ItemParam;

use Exception;

class Type
{
    public const TYPE_DEFAULT = 'default';

    public const TYPE_FILE = 'file';

    public const TYPE_FILE_SKIP_CHECK = 'file:skipcheck';

    /**
     * @param string $type see Type::TYPE_*
     * @throws Exception if $type is invalid
     */
    public static function handle(string $type): string
    {
        if (!in_array($type, [self::TYPE_DEFAULT, self::TYPE_FILE, self::TYPE_FILE_SKIP_CHECK])) {
            throw new Exception(
                'Invalid type [' . $type . '], use \Alfred\Workflows\ItemParam\Type::TYPE_*'
            );
        }

        return $type;
    }
}
