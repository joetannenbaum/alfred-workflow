<?php

namespace Alfred\Workflows\ItemParam;

use Exception;

class Text
{
    const TYPE_COPY = 'copy';

    const TYPE_LARGE_TYPE = 'largetype';

    /**
     * @param string $type see \Alfred\Workflows\ItemParam\Text::TYPE_*
     * @param string $text
     * @throws Exception when $type is invalid
     */
    public static function handle(string $type, string $text): array
    {
        if (!in_array($type, [self::TYPE_COPY, self::TYPE_LARGE_TYPE])) {
            throw new Exception('Invalid type [' . $type . '], use \Alfred\Workflows\ItemParam\Text::TYPE_*');
        }

        return [$type => $text];
    }
}
