<?php

namespace Alfred\Workflows\ItemParam;

trait HasParams
{
    protected $params = [];

    public function toArray()
    {
        ksort($this->params);

        return $this->params;
    }

    /**
     * Merge a param if it exists, create a new key if it doesn't
     */
    protected function mergeParam(string $key, array $value): void
    {
        if (array_key_exists($key, $this->params)) {
            $this->params[$key] = array_merge($this->params[$key], $value);
        } else {
            $this->params[$key] = $value;
        }
    }
}
