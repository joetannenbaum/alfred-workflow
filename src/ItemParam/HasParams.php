<?php

namespace Alfred\Workflows\ItemParam;

trait HasParams
{
    protected array $params = [];

    public function toArray(): array
    {
        ksort($this->params);

        return $this->params;
    }

    /**
     * Merge a param if it exists, create a new key if it doesn't
     */
    protected function mergeParam(string $key, array $value): void
    {
        if ($this->hasParam($key)) {
            $this->params[$key] = array_merge($this->params[$key], $value);
        } else {
            $this->params[$key] = $value;
        }
    }

    protected function hasParam(string $key)
    {
        return array_key_exists($key, $this->params);
    }
}
