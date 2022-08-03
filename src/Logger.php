<?php

namespace Alfred\Workflows;

class Logger
{
    /**
     * @var \Alfred\Workflows\Alfred
     */
    protected $stream;

    protected $prefix = 'alfred';

    public function __construct()
    {
        $alfred = new Alfred();

        if ($alfred->debugging()) {
            $this->stream = fopen('php://stderr', 'w');
        }
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    public function info($message)
    {
        if (!$this->stream) {
            // We're not in debugging mode, don't do anything
            return;
        }

        fwrite(
            $this->stream,
            sprintf(
                '[%s] %s',
                $this->prefix,
                $this->messageToString($message)
            ),
        );
    }

    protected function messageToString($message)
    {
        if (is_bool($message)) {
            return $message ? 'true' : 'false';
        }

        if (is_scalar($message)) {
            return $message;
        }

        return json_encode($message);
    }
}
