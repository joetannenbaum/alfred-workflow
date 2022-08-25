<?php

namespace Alfred\Workflows\ItemParam;

trait HasVariables
{
    use HasParams;

    /**
     * Variables can be passed out of the script filter within a variables object.
     * This is useful for two things. Firstly, these variables will be passed out of
     * the script filter's outputs when actioning a result. Secondly, any variables
     * passed out of a script will be passed back in as environment variables when the
     * script is run within the same session. This can be used for very simply
     * managing state between runs as the user types input or when the script is set
     * to re-run after an interval.
     *
     * @param string|array $key
     * @param mixed $value
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/#variables
     */
    public function variable($key, $value = null): self
    {
        if (is_array($key)) {
            return $this->variables($key);
        }

        $this->mergeParam('variables', [$key => $value]);

        return $this;
    }

    /**
     * Variables can be passed out of the script filter within a variables object.
     * This is useful for two things. Firstly, these variables will be passed out of
     * the script filter's outputs when actioning a result. Secondly, any variables
     * passed out of a script will be passed back in as environment variables when the
     * script is run within the same session. This can be used for very simply
     * managing state between runs as the user types input or when the script is set
     * to re-run after an interval.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/#variables
     */
    public function variables(array $variables): self
    {
        $this->mergeParam('variables', $variables);

        return $this;
    }
}
