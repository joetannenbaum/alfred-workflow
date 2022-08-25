<?php

namespace Alfred\Workflows\ItemParam;

trait HasValidity
{
    /**
     * If this item is valid or not. If an item is valid then Alfred will action this
     * item when the user presses return. If the item is not valid, Alfred will do
     * nothing.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function valid(bool $valid = true): self
    {
        $this->params['valid'] = !!$valid;

        return $this;
    }

    /**
     * Mark this item as invalid, Alfred will do nothing with this item.
     */
    public function invalid(): self
    {
        return $this->valid(false);
    }
}
