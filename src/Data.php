<?php

namespace Alfred\Workflows;

use Exception;

class Data extends AbstractData
{
    /**
     * @var string
     */
    protected string $filename = 'data.json';

    /**
     * @throws Exception when the workflow bundle ID is missing
     * @throws Exception when the directory is not set
     */
    public function dir(): string
    {
        return $this->validateDir($this->alfred->workflowData(), 'data');
    }
}
