<?php

namespace Alfred\Workflows\ItemParam;

class Type
{
    const TYPE_DEFAULT = 'default';

    const TYPE_FILE = 'file';

    const TYPE_FILE_SKIPCHECK = 'file:skipcheck';

    /**
     * @param \Alfred\Workflows\ItemParam\Type::TYPE_* $type
     * @param bool $verify_existence When used with $type \Alfred\Workflows\Result::TYPE_FILE
     * @throws \Exception if $type is invalid
     * @return string
     */
    public static function handle($type, $verify_existence)
    {
        if (!in_array($type, [self::TYPE_DEFAULT, self::TYPE_FILE, self::TYPE_FILE_SKIPCHECK])) {
            throw new \Exception(
                'Invalid type [' . $type . '], use \Alfred\Workflows\ItemParam\Type::TYPE_*'
            );
        }

        if ($type === self::TYPE_FILE && $verify_existence === false) {
            return self::TYPE_FILE_SKIPCHECK;
        }

        return $type;
    }
}
