<?php

namespace Alfred\Workflows;

use SimpleXMLElement;

class Result
{
    protected $uid;

    protected $arg;

    protected $valid = true;

    protected $autocomplete;

    protected $title;

    protected $subtitle;

    protected $subtitles = [];

    protected $icon;

    protected $default = 'default';

    protected $copy;

    protected $largetype;

    public function generateUid($str)
    {
        $this->uid = md5($str);

        return $this;
    }

    protected function setValid($valid = true)
    {
        $this->valid = !!$valid;

        return $this;
    }

    protected function setDefault($default)
    {
        if (in_array($default, ['default', 'file', 'file:skipcheck'])) {
            $this->default = $default;
        }

        return $this;
    }

    protected function setSubtitle($subtitle, $mod = null)
    {
        if ($mod === null) {
            $this->subtitle = $subtitle;

            return $this;
        }

        if (!in_array($mod, ['shift', 'fn', 'ctrl', 'alt', 'cmd'])) {
            return $this;
        }

        $this->subtitles[$mod] = $subtitle;

        return $this;
    }

    public function toArray()
    {
        $attrs = ['uid', 'arg', 'autocomplete', 'default', 'title', 'subtitle'];

        $result = [
            'text' => [],
            'mods' => [],
        ];

        foreach ($attrs as $attr) {
            if ($this->$attr !== null) {
                $result[$attr] = $this->$attr;
            }
        }

        if ($this->icon !== null) {
            $result['icon'] = ['path' => $this->icon];
        }

        $text_attrs = ['copy', 'largetype'];

        foreach ($text_attrs as $attr) {
            if ($this->$attr !== null) {
                $result['text'][$attr] = $this->$attr;
            }
        }

        foreach ($this->subtitles as $mod => $subtitle) {
            $result['mods'][$mod] = [
                'valid'    => true,
                'subtitle' => $subtitle,
            ];
        }

        return $result;
    }

    public function __get($property)
    {
        return $this->$property;
    }

    public function __call($method, $args)
    {
        $setter = 'set' . ucwords($method);

        if (method_exists($this, $setter)) {
            call_user_func_array([$this, $setter], $args);

            return $this;
        }

        if (property_exists($this, $method)) {
            $this->$method = reset($args);

            return $this;
        }
    }
}
