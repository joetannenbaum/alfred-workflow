<?php

namespace Alfred\Workflows\ItemParam;

class Text
{
    const TYPE_COPY = 'copy';

    const TYPE_LARGETYPE = 'largetype';

    /**
     * @param \Alfred\Workflows\ItemParam\Text::TYPE_* $type
     * @param string $text
     * @throws \Exception when $type is invalid
     * @return array
     */
    public static function handle($type, $text)
    {
        if (!in_array($type, [self::TYPE_COPY, self::TYPE_LARGETYPE])) {
            throw new \Exception('Invalid type [' . $type . '], use \Alfred\Workflows\ItemParam\Text::TYPE_*');
        }

        return [$type => $text];
    }
}
