<?php

namespace Alfred\Workflows;

class Data extends AbstractData
{
    /**
     * @var string
     */
    protected $filename = 'data.json';

    public function dir()
    {
        return $this->validateDir($this->alfred->workflowData(), 'data');
    }
}
