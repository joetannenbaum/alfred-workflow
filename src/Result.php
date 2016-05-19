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

    protected $icon;

    protected $type;

    protected $text = [];

    protected $quicklookurl;

    protected $mods = [];

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

    protected function setType($type, $verify_existence = true)
    {
        if (in_array($type, ['default', 'file', 'file:skipcheck'])) {
            if ($type === 'file' && $verify_existence === false) {
                $type = 'file:skipcheck';
            }

            $this->type = $type;
        }

        return $this;
    }

    protected function setIcon($path, $type = null)
    {
        $this->icon = [
            'path' => $path,
        ];

        if (in_array($type, ['fileicon', 'filetype'])) {
            $this->icon['type'] = $type;
        }

        return $this;
    }

    protected function setFileiconIcon($path)
    {
        return $this->setIcon($path, 'fileicon');
    }

    protected function setFiletypeIcon($path)
    {
        return $this->setIcon($path, 'filetype');
    }

    protected function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    protected function setCopy($copy)
    {
        $this->text['copy'] = $copy;

        return $this;
    }

    protected function setLargetype($largetype)
    {
        $this->text['largetype'] = $largetype;

        return $this;
    }

    protected function setMod($mod, $subtitle, $arg, $valid = true)
    {
        if (!in_array($mod, ['shift', 'fn', 'ctrl', 'alt', 'cmd'])) {
            return $this;
        }

        $this->mods[$mod] = compact('subtitle', 'arg', 'valid');

        return $this;
    }

    protected function setCmd($subtitle, $arg, $valid = true)
    {
        return $this->setMod('cmd', $subtitle, $arg, $valid);
    }

    protected function setShift($subtitle, $arg, $valid = true)
    {
        return $this->setMod('shift', $subtitle, $arg, $valid);
    }

    public function toArray()
    {
        $attrs = [
            'uid',
            'arg',
            'autocomplete',
            'title',
            'subtitle',
            'type',
            'valid',
            'quicklookurl',
            'icon',
            'mods',
            'text',
        ];

        $result = [];

        foreach ($attrs as $attr) {
            if (is_array($this->$attr)) {
                if (count($this->$attr) > 0) {
                    $result[$attr] = $this->$attr;
                }
                continue;
            }

            if ($this->$attr !== null) {
                $result[$attr] = $this->$attr;
            }
        }

        ksort($result);

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
