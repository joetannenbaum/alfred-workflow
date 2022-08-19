<?php

namespace Alfred\Workflows;

class Items
{
    const SORT_ASC = 'asc';
    const SORT_DESC = 'desc';

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
    public function sort($property = 'title', string $direction = self::SORT_ASC): Items
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
     */
    public function filter(?string $query, string $property = 'title'): Items
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

    public function all(): array
    {
        return $this->items;
    }
}
