<?php

namespace Alfred\Workflows\ItemParam;

class Action
{
    use HasParams;

    /**
     * Explicitly let Alfred know to interpret argument as text
     *
     * @param string|array $text
     */
    public function text($text): Action
    {
        $this->params['text'] = $text;

        return $this;
    }

    /**
     * Explicitly let Alfred know to interpret argument as a URL
     *
     * @param string|array $url
     */
    public function url($url): Action
    {
        $this->params['url'] = $url;

        return $this;
    }

    /**
     * Explicitly let Alfred know to interpret argument as a file
     *
     * @param string|array $file
     */
    public function file($file): Action
    {
        $this->params['file'] = $file;

        return $this;
    }

    /**
     * Let Alfred decide how to handle argument
     *
     * @param string|array $auto
     */
    public function auto($auto): Action
    {
        $this->params['auto'] = $auto;

        return $this;
    }
}
