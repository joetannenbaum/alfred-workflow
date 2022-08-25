<?php

namespace Alfred\Workflows;

use Exception;

class Cache extends AbstractData
{
    protected string $filename = 'cache.json';

    /**
     * @throws Exception when the workflow bundle ID is missing
     * @throws Exception when the directory is not set
     */
    public function dir(): string
    {
        return $this->validateDir($this->alfred->workflowCache(), 'cache');
    }
}
