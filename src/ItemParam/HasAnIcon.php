<?php

namespace Alfred\Workflows\ItemParam;

use Exception;

trait HasAnIcon
{
    /**
     * The icon displayed in the result row. Workflows are run from their
     * workflow folder, so you can reference icons stored in your
     * workflow relatively.
     *
     * By omitting the "type", Alfred will load the file path itself, for
     * example a png. By using "type": "fileicon", Alfred will get the icon
     * for the specified path. Finally, by using "type": "filetype", you can
     * get the icon of a specific file, for example "path": "public.png"
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     *
     * @param string $path
     * @param string|null $type see \Alfred\Workflows\Item\Icon::TYPE_
     * @throws Exception when $type is invalid
     */
    public function icon(string $path, ?string $type = null): self
    {
        $this->params['icon'] = Icon::handle($path, $type);

        return $this;
    }

    /**
     * Alfred will get the icon for the specified file path. For example:
     *
     * my-image.png -> png icon
     * Alfred.app -> Alfred Icon
     * important-doc.pdf -> pdf icon
     * ~/Desktop -> desktop icon
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function iconForFilePath(string $path)
    {
        return $this->icon($path, Icon::TYPE_FILE_ICON);
    }

    /**
     * Get the icon of a specific file type, for example:
     * 'public.folder', 'jpg', 'png', 'pdf', 'sketch', etc.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function iconForFileType(string $type): self
    {
        return $this->icon($type, Icon::TYPE_FILE_TYPE);
    }
}
