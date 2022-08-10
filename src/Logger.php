<?php

namespace Alfred\Workflows;

class Logger
{
    /**
     * @var false|resource
     */
    protected $stream;

    protected string $prefix = 'alfred';

    protected Alfred $alfred;

    public function __construct()
    {
        $this->alfred = new Alfred();

        if ($this->alfred->debugging()) {
            $this->setup();
        }
    }

    protected function setup()
    {
        $this->stream = fopen('php://stderr', 'w');
        $this->setTimezone();

        $this->setPrefix(
            $this->alfred->workflowName() ?: $this->alfred->workflowBundleId() ?: $this->prefix
        );
    }

    /**
     * Set the logging timezone based on the system timezone
     */
    protected function setTimezone()
    {
        $shortName = exec('date +%Z');
        $offset = exec('date +%z');

        $leading = substr($offset, 0, 1); // - or +
        $hours = substr($offset, 1, 2);
        $minutes = substr($offset, 3, 4);

        $offsetInSeconds = ($hours * 60 * 60) + ($minutes * 60);

        if ($leading === '-') {
            $offsetInSeconds = -$offsetInSeconds;
        }

        $longName = timezone_name_from_abbr(
            $shortName,
            $offsetInSeconds,
            substr($shortName, 1, 2) === 'DT'
        );

        if ($longName) {
            date_default_timezone_set($longName);
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
                "[%s] %s %s\n",
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
