<?php

namespace Alfred\Workflows\ItemParam;

use Exception;

class Icon
{
    /**
     * By using "type": "fileicon", Alfred will get the icon
     * for the specified path.
     */
    const TYPE_FILE_ICON = 'fileicon';

    /**
     * Finally, by using "type": "filetype", you can get the
     * icon of a specific file, for example "path": "public.png"
     */
    const TYPE_FILE_TYPE = 'filetype';

    /**
     * @param string $path
     * @param string|null $type see \Alfred\Workflows\ItemParam\Icon::TYPE_*
     * @throws Exception when $type is invalid
     */
    public static function handle(string $path, ?string $type = null): array
    {
        $icon = [
            'path' => $path,
        ];

        if ($type && !in_array($type, [self::TYPE_FILE_ICON, self::TYPE_FILE_TYPE])) {
            throw new Exception('Invalid icon type [' . $type . '], use \Alfred\Workflows\ItemParam\Icon::TYPE_*');
        }

        if ($type) {
            $icon['type'] = $type;
        }

        return $icon;
    }
}
