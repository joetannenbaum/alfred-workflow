<?php

namespace Alfred\Workflows\ItemParam;

class Action
{
    use HasParams;

    public function text(string|array $text): Action
    {
        $this->params['text'] = $text;

        return $this;
    }

    public function url(string $url): Action
    {
        $this->params['url'] = $url;

        return $this;
    }

    public function file(string $file): Action
    {
        $this->params['file'] = $file;

        return $this;
    }

    public function auto(string $auto): Action
    {
        $this->params['auto'] = $auto;

        return $this;
    }
}
