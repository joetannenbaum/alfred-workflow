<?php

namespace Alfred\Workflows;

class Logger
{
    /**
     * @var false|resource
     */
    protected $stream;

    protected string $prefix = 'alfred';

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

    /**
     * @param mixed $message
     * @return false|int
     */
    public function info($message)
    {
        if (!$this->stream) {
            // We're not in debugging mode, don't do anything
            return false;
        }

        return fwrite(
            $this->stream,
            sprintf(
                '[%s] %s %s' . "\n",
                $this->prefix,
                date('Y-m-d H:i:s'),
                $this->messageToString($message)
            ),
        );
    }

    /**
     * Alias of `info` method.
     * @param mixed $message
     * @return false|int
     */
    public function log($message)
    {
        return $this->info($message);
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
