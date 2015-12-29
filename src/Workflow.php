<?php

namespace Alfred\Workflows;

require 'Result.php';

use SimpleXMLElement;

class Workflow
{
    protected $results = [];

    public function result()
    {
        $result = new Result;

        $this->results[] = $result;

        return $result;
    }

    public function sortResults($direction = 'asc', $property = 'title')
    {
        usort($this->results, function ($a, $b) use ($direction, $property) {
            if ($direction === 'asc') {
                return $a->$property > $b->$property;
            }

            return $a->$property < $b->$property;
        });

        return $this;
    }

    public function filterResults($query, $property = 'title')
    {
        if ($query === null || trim($query) === '') {
            return $this;
        }

        $this->results = array_filter($this->results, function ($result) use ($query, $property) {
                return strstr($result->$property, $query) !== false;
            });

        return $this;
    }

    public function xml()
    {
        $items = new SimpleXMLElement('<items></items>');

        foreach ($this->results as $result) {
            $result->xml($items->addChild('item'));
        }

        return $items->asXML();
    }
}
