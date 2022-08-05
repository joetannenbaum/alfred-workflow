<?php

namespace Alfred\Workflows\ItemParam;

trait MergesParams
{
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
