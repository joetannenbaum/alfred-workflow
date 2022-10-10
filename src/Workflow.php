<?php

namespace Alfred\Workflows;

use Alfred\Workflows\ItemParam\HasParams;
use Alfred\Workflows\ItemParam\HasVariables;
use Exception;

class Workflow
{
    use HasParams;
    use HasVariables;

    protected Alfred $alfred;

    protected Cache $cache;

    protected Data $data;

    protected Logger $logger;

    protected Items $items;

    public function __construct()
    {
        $this->logger = new Logger();
        $this->alfred = new Alfred();
        $this->cache = new Cache($this->logger);
        $this->data = new Data($this->logger);
        $this->items = new Items();
    }

    /**
     * Add an item to the workflow results
     */
    public function item(): Item
    {
        return $this->items->add();
    }

    /**
     * Set variables, arguments, and config from a Script Action
     *
     * @link https://www.alfredapp.com/help/workflows/utilities/json/
     * @return RunScript
     */
    public function setFromRunScript()
    {
        return new RunScript();
    }

    /**
     * Access the collection of items
     */
    public function items(): Items
    {
        return $this->items;
    }

    /**
     * Access the Alfred instance to read Alfred-specific environment variables
     */
    public function alfred(): Alfred
    {
        return $this->alfred;
    }

    /**
     * Access the Cache instance to read and write from the workflow cache
     */
    public function cache(): Cache
    {
        return $this->cache;
    }

    /**
     * Access the Cache instance to read and write from the workflow cache
     */
    public function data(): Data
    {
        return $this->data;
    }

    /**
     * Access the Logger instance to log information while the Alfred debugger is open
     */
    public function logger(): Logger
    {
        return $this->logger;
    }

    /**
     * If a $key is provided, access that env variable with a $default fallback.
     * If no $key is provided, return all env variables as an array.
     *
     * @return string|null|array
     */
    public function env(string $key = null, $default = null)
    {
        if ($key === null) {
            return $_SERVER;
        }

        if (array_key_exists($key, $_SERVER)) {
            return $_SERVER[$key];
        }

        return $default;
    }

    /**
     * Get the first argument passed in from $argv,
     * excluding the called script.
     *
     * Returns null when there is no argument passed.
     */
    public function argument(): ?string
    {
        $args = $this->arguments();

        if (count($args) === 0) {
            return null;
        }

        return $args[0];
    }

    /**
     * Get all the arguments passed in from $argv,
     * excluding the called script.
     */
    public function arguments(): array
    {
        $args = $this->env('argv') ?: [];

        // We don't need the input script, probably
        array_shift($args);

        return $args;
    }

    /**
     * Scripts can be set to re-run automatically after an interval with a value of
     * 0.1 to 5.0 seconds. The script will only be re-run if the script filter is
     * still active and the user hasn't changed the state of the filter by typing
     * and triggering a re-run.
     *
     * @throws Exception if $seconds is not numeric
     * @throws Exception if $second is not within 0.1 and 5.0
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/#rerun
     */
    public function rerun(float $seconds): self
    {
        if (!is_numeric($seconds)) {
            throw new Exception('Re-run $seconds must be numeric');
        }

        if ($seconds < 0.1 || $seconds > 5) {
            throw new Exception('Re-run $seconds must be between 0.1 and 5.0 seconds');
        }

        $this->params['rerun'] = $seconds;

        return $this;
    }

    /**
     * In Alfred 5 and above, this preserves the given item order while allowing Alfred
     * to retain knowledge of your items, like your current selection during a re-run.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/#uid
     */
    public function skipKnowledge($skip = true): self
    {
        $this->params['skipknowledge'] = $skip;

        return $this;
    }

    /**
     * @throws Exception when "title" is missing from item (required property)
     */
    public function output(bool $echo = true): string
    {
        $items = array_map(function ($item) {
            return $item->toArray();
        }, array_values($this->items->all()));

        foreach ($items as $item) {
            if (!array_key_exists('title', $item)) {
                throw new Exception('Title missing from item: ' . json_encode($item));
            }
        }

        $output = compact('items');

        $output = array_merge($output, $this->params);

        $json = json_encode($output);

        if ($echo) {
            echo $json;
        }

        return $json;
    }
}
