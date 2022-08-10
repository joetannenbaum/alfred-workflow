<?php

namespace Alfred\Workflows;

use Exception;

abstract class AbstractData
{
    protected string $filename;

    protected Logger $logger;

    protected Alfred $alfred;

    public function __construct(Logger $logger)
    {
        $this->alfred = new Alfred();
        $this->logger = $logger;
    }

    abstract public function dir(): string;

    public function setFilename($name)
    {
        $this->filename = $name;
    }

    /**
     * @throws Exception when filename is missing (when both the argument and the default are empty)
     */
    protected function getFilename($filename = null)
    {
        $filename = $filename ?: $this->filename;

        if ($filename) {
            return $filename;
        }

        throw new Exception('Missing filename! Either pass as argument or use `setFilename` method');
    }

    /**
     * @throws Exception when filename is missing (when both the argument and the default are empty)
     */
    public function path(?string $filename = null): string
    {
        return $this->dir() . '/' . $this->getFilename($filename);
    }

    public function write($data, ?string $filename = null)
    {
        $path = $this->path($filename);

        $this->logger->info('Writing to: ' . $path);

        return file_put_contents($path, $data);
    }

    /**
     * @throws Exception when filename is missing (when both the argument and the default are empty)
     */
    public function read(?string $filename = null)
    {
        $filename = $filename ?: $this->filename;

        if (!$filename) {
            throw new Exception('Missing filename! Either pass as second argument or use `setFilename` method');
        }

        $path = $this->path($filename);

        if (!file_exists($path)) {
            $this->logger->info('File does not exist, creating: ' . $path);
            touch($path);
        }

        $this->logger->info('Reading from: ' . $path);

        return file_get_contents($path);
    }

    public function writeJson($data, ?string $filename = null)
    {
        return $this->write(json_encode($data), $filename);
    }

    /**
     * @throws Exception when filename is missing (when both the argument and the default are empty)
     */
    public function readJson(?string $filename = null, bool $assoc = true)
    {
        return json_decode($this->read($filename), $assoc);
    }

    /**
     * @throws Exception when the workflow bundle ID is missing
     * @throws Exception when the directory is not set
     */
    protected function validateDir($path, $type)
    {
        if ($path) {
            if (!is_dir($path)) {
                mkdir($path);
            }

            return $path;
        }

        if (!$this->alfred->workflowBundleid()) {
            throw new Exception('The workflow bundle ID must be set for the ' . $type . ' directory to exist.');
        }

        throw new Exception(ucwords($type) . ' directory not set!');
    }
}
