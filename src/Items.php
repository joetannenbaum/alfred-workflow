<?php

namespace Alfred\Workflows;

class Items
{
    public const SORT_ASC = 'asc';
    public const SORT_DESC = 'desc';

    protected array $items = [];

    /**
     * Add an item to the workflow items list
     */
    public function add(): Item
    {
        $item = new Item();

        $this->items[] = $item;

        return $item;
    }

    /**
     * Sort the current items
     *
     * @param string|callable $property
     * @param string $direction see Items::SORT_*
     */
    public function sort($property = 'title', string $direction = self::SORT_ASC): self
    {
        if (is_callable($property)) {
            usort($this->items, $property);

            return $this;
        }

        usort($this->items, function ($a, $b) use ($direction, $property) {
            if ($direction === self::SORT_ASC) {
                return $a->$property > $b->$property ? 1 : -1;
            }

            return $a->$property < $b->$property ? 1 : -1;
        });

        return $this;
    }

    /**
     * Filter current items (destructive)
     *
     * @param string|callable $query
     */
    public function filter($query, string $property = 'title'): self
    {
        if (is_callable($query)) {
            $this->items = array_filter($this->items, $query);

            return $this;
        }

        if ($query === null || trim($query) === '') {
            return $this;
        }

        $query = (string) $query;

        $this->items = array_filter($this->items, function ($result) use ($query, $property) {
            return stristr($result->$property, $query) !== false;
        });

        return $this;
    }

    public function all(): array
    {
        return $this->items;
    }
}
