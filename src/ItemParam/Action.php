<?php

namespace Alfred\Workflows\ItemParam;

class Action
{
    use HasParams;

    public function __construct(string|array $text)
    {
        $this->text($text);
    }

    public static function fromText(string|array $text): Action
    {
        return new Action($text);
    }

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
