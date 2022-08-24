<?php

namespace Alfred\Workflows\ItemParam;

trait HasSubtitle
{
    use HasParams;

    /**
     * The subtitle displayed in the result row.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function subtitle(string $subtitle): self
    {
        $this->params['subtitle'] = $subtitle;

        return $this;
    }
}
