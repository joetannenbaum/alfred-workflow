<?php

namespace Alfred\Workflows\ItemParam;

class Icon
{
    /**
     * By using "type": "fileicon", Alfred will get the icon
     * for the specified path.
     */
    const TYPE_FILEICON = 'fileicon';

    /**
     * Finally, by using "type": "filetype", you can get the
     * icon of a specific file, for example "path": "public.png"
     */
    const TYPE_FILETYPE = 'filetype';

    /**
     * @param string $path
     * @param \Alfred\Workflows\ItemParam\Icon::TYPE_* $type
     * @throws \Exception when $type is invalid
     * @return array
     */
    public static function handle($path, $type)
    {
        $icon = [
            'path' => $path,
        ];

        if ($type && !in_array($type, [self::TYPE_FILEICON, self::TYPE_FILETYPE])) {
            throw new \Exception('Invalid icon type [' . $type . '], use \Alfred\Workflows\ItemParam\Icon::TYPE_*');
        }

        if ($type) {
            $icon['type'] = $type;
        }

        return $icon;
    }
}
