<?php

namespace Alfred\Workflows;

class Cache extends AbstractData
{
    /**
     * @var string
     */
    protected $filename = 'cache.json';

    public function dir()
    {
        return $this->validateDir($this->alfred->workflowCache(), 'cache');
    }
}
