<?php

namespace Alfred\Workflows;

abstract class AbstractData
{
    /**
     * @var \Alfred\Workflows\Alfred
     */
    protected $alfred;

    public function __construct()
    {
        $this->alfred = new Alfred();
    }

    abstract public function dir();

    public function setFilename($name)
    {
        $this->filename = $name;
    }

    protected function getFilename($filename = null)
    {
        $filename = $filename ?: $this->filename;

        if ($filename) {
            return $filename;
        }

        throw new \Exception('Missing filename! Either pass as second argument or use `setFilename` method');
    }

    public function path($filename = null)
    {
        return $this->dir() . '/' . $this->getFilename($filename);
    }

    public function write($data, $filename = null)
    {
        return file_put_contents($this->path($filename), $data);
    }

    public function read($filename = null)
    {
        $filename = $filename ?: $this->filename;

        if (!$filename) {
            throw new \Exception('Missing filename! Either pass as second argument or use `setFilename` method');
        }

        $path = $this->path($filename);

        if (!file_exists($path)) {
            touch($path);
        }

        $data = file_get_contents($path);

        return $data;
    }

    public function writeJson($data, $filename = null)
    {
        return $this->write(json_encode($data), $filename);
    }

    public function readJson($filename = null, $assoc = true)
    {
        return json_decode($this->read($filename), $assoc);
    }

    protected function validateDir($path, $type)
    {
        if ($path) {
            if (!is_dir($path)) {
                mkdir($path);
            }

            return $path;
        }

        if (!$this->alfred->workflowBundleid()) {
            throw new \Exception('The workflow bundle ID must be set for the ' . $type . ' directory to exist.');
        }

        throw new \Exception(ucwords($type) . ' directory not set!');
    }
}
