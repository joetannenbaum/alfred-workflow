<?php

namespace Alfred\Workflows;

use SimpleXMLElement;

class Result
{
    protected $uid;

    protected $arg;

    protected $valid = 'yes';

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

    public function xml(SimpleXMLElement $item)
    {
        $this->addXmlAttr($item);
        $this->addXmlChildren($item);

        return $item;
    }

    protected function addXmlAttr(SimpleXMLElement $item)
    {
        $attrs = ['uid', 'arg', 'autocomplete', 'default'];

        foreach ($attrs as $attr) {
            if ($this->$attr !== null) {
                $item->addAttribute($attr, $this->$attr);
            }
        }
    }

    protected function addXmlChildren(SimpleXMLElement $item)
    {
        $attrs = ['title', 'subtitle', 'icon'];

        foreach ($attrs as $attr) {
            if ($this->$attr !== null) {
                $item->$attr = $this->$attr;
            }
        }

        $text_attrs = ['copy', 'largetype'];

        foreach ($text_attrs as $attr) {
            if ($this->$attr !== null) {
                $text = $item->addChild('text', $this->$attr);
                $text->addAttribute('type', $attr);
            }
        }

        foreach ($this->subtitles as $mod => $subtitle) {
            $text = $item->addChild('subtitle', $subtitle);
            $text->addAttribute('mod', $mod);
        }
    }

    protected function setValid($valid = true)
    {
        $this->valid = (!!$valid === false) ? 'no' : 'yes';

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
