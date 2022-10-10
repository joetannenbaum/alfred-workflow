<?php

namespace Alfred\Workflows\ItemParam;

trait HasConfig
{
    use HasParams;

    /**
     * The `config` object enables dynamic (and overriding) configuration of
     * the workflow objects connected to the output of the JSON Utility.
     *
     * The easiest way to find out which configuration fields are available
     * for an object is to copy the object configuration from the right-click
     * popup menu for the selected workflow object on the canvas.
     *
     * Only the included fields will be overridden, allowing for partial dynamic
     * configuration of a workflow object.
     *
     * The fields are generally self-explanatory, but if you have trouble identifying
     * a field, set it to a unique value in the object's configuration sheet and copy
     * the configuration again. You'll see the value you set.
     *
     * @param string|array $key
     * @param mixed $value
     *
     * @link https://www.alfredapp.com/help/workflows/utilities/json/
     */
    public function config($key, $value = null): self
    {
        if (is_array($key)) {
            $this->mergeParam('config', $key);
            return $this;
        }

        $this->mergeParam('config', [$key => $value]);

        return $this;
    }
}
