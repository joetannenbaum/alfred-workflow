<?php

namespace Alfred\Workflows;

class Workflow
{
    /**
     * @var Item[]
     */
    protected array $items = [];

    protected array $variables = [];

    /**
     * @var number|float
     */
    protected $rerun = null;

    protected Alfred $alfred;

    protected Cache $cache;

    protected Data $data;

    protected Logger $logger;

    public function __construct()
    {
        $this->alfred = new Alfred();
        $this->cache = new Cache();
        $this->data = new Data();
        $this->logger = new Logger();
    }

    /**
     * Add a item to the workflow
     *
     * @return \Alfred\Workflows\Item
     */
    public function item(): Item
    {
        $item = new Item();

        $this->items[] = $item;

        return $item;
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
     * Variables can be passed out of the script filter within a variables object.
     * This is useful for two things. Firstly, these variables will be passed out of
     * the script filter's outputs when actioning a result. Secondly, any variables
     * passed out of a script will be passed back in as environment variables when the
     * script is run within the same session. This can be used for very simply
     * managing state between runs as the user types input or when the script is set
     * to re-run after an interval.
     *
     * @param string $key
     * @param mixed $value
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/#variables
     *
     * @return \Alfred\Workflows\Workflow
     */
    public function variable($key, $value): Workflow
    {
        $this->variables[$key] = $value;

        return $this;
    }

    /**
     * Scripts can be set to re-run automatically after an interval with a value of
     * 0.1 to 5.0 seconds. The script will only be re-run if the script filter is
     * still active and the user hasn't changed the state of the filter by typing
     * and triggering a re-run.
     *
     * @param number|float $seconds
     * @throws \Exception if $seconds is not numeric
     * @throws \Exception if $second is not within 0.1 and 5.0
     */
    public function rerun($seconds): Workflow
    {
        if (!is_numeric($seconds)) {
            throw new \Exception('Re-run $seconds must be numeric');
        }

        if ($seconds < 0.1 || $seconds > 5) {
            throw new \Exception('Re-run $seconds must be between 0.1 and 5.0 seconds');
        }

        $this->rerun = $seconds;

        return $this;
    }

    /**
     * Sort the current items
     *
     * @param string $direction
     * @param string $property
     *
     * @return \Alfred\Workflows\Workflow
     */
    public function sortItems($direction = 'asc', $property = 'title')
    {
        usort($this->items, function ($a, $b) use ($direction, $property) {
            if ($direction === 'asc') {
                return $a->$property > $b->$property ? 1 : -1;
            }

            return $a->$property < $b->$property ? 1 : -1;
        });

        return $this;
    }

    /**
     * Filter current items (destructive)
     *
     * @param string $query
     * @param string $property
     *
     * @return \Alfred\Workflows\Workflow
     */
    public function filterItems($query, $property = 'title')
    {
        if ($query === null || trim($query) === '') {
            return $this;
        }

        $query = (string) $query;

        $this->items = array_filter($this->items, function ($result) use ($query, $property) {
            return stristr($result->$property, $query) !== false;
        });

        return $this;
    }

    /**
     * Output
     *
     * @return string
     */
    public function output($echo = true)
    {
        /**
         * Force IDE to understand that this is now an array of arrays
         * @var array
         */
        $items = array_map(function ($item) {
            return $item->toArray();
        }, array_values($this->items));

        foreach ($items as $item) {
            if (!array_key_exists('title', $item)) {
                throw new \Exception('Title missing from item: ' . json_encode($item));
            }
        }

        $output = compact('items');

        if (!empty($this->variables)) {
            $output['variables'] = $this->variables;
        };

        if ($this->rerun !== null) {
            $output['rerun'] = $this->rerun;
        }

        $json = json_encode($output);

        if ($echo) {
            echo $json;
        }

        return $json;
    }
}
