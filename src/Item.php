<?php

namespace Alfred\Workflows;

use Alfred\Workflows\ItemParam\Icon;
use Alfred\Workflows\ItemParam\Mod;
use Alfred\Workflows\ItemParam\Text;
use Alfred\Workflows\ItemParam\Type;

class Item
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * If this item is valid or not. If an item is valid then Alfred
     * will action this item when the user presses return.
     * If the item is not valid, Alfred will do nothing.
     *
     * @param bool $valid
     * @return \Alfred\Workflows\Item
     */
    public function valid($valid = true)
    {
        $this->params['valid'] = !!$valid;

        return $this;
    }

    /**
     * By specifying "type": "file", this makes Alfred treat your result as a file
     * on your system. This allows the user to perform actions on the file like
     * they can with Alfred's standard file filters.
     *
     * When returning files, Alfred will check if the file exists before presenting
     * that result to the user. This has a very small performance implication but
     * makes the results as predictable as possible. If you would like Alfred to
     * skip this check as you are certain that the files you are returning exist,
     * you can use "type": "file:skipcheck".
     *
     * @param \Alfred\Workflows\Item\Type::TYPE_* $type
     * @param bool $verify_existence When used with $type \Alfred\Workflows\Item::TYPE_FILE
     * @return \Alfred\Workflows\Item
     */
    public function type($type, $verify_existence = true)
    {
        $this->params['type'] = Type::handle($type, $verify_existence);

        return $this;
    }

    /**
     * The icon displayed in the result row. Workflows are run from their
     * workflow folder, so you can reference icons stored in your
     * workflow relatively.
     *
     * By omitting the "type", Alfred will load the file path itself, for
     * example a png. By using "type": "fileicon", Alfred will get the icon
     * for the specified path. Finally, by using "type": "filetype", you can
     * get the icon of a specific file, for example "path": "public.png"
     *
     * @param string $path
     * @param \Alfred\Workflows\Item\Icon::TYPE_* $type
     * @return \Alfred\Workflows\Item
     */
    public function icon($path, $type = null)
    {
        $this->params['icon'] = Icon::handle($path, $type);

        return $this;
    }

    /**
     * Alfred will get the icon for the specified path.
     *
     * @param string $path
     * @return \Alfred\Workflows\Item
     */
    public function fileiconIcon($path)
    {
        return $this->icon($path, Icon::TYPE_FILEICON);
    }

    /**
     * Get the icon of a specific file,
     * for example "path": "public.png"
     *
     * @param string $path
     * @return \Alfred\Workflows\Item
     */
    public function filetypeIcon($path)
    {
        return $this->icon($path, Icon::TYPE_FILETYPE);
    }

    /**
     * The subtitle displayed in the result row.
     *
     * @param string $subtitle
     * @return \Alfred\Workflows\Item
     */
    public function subtitle($subtitle)
    {
        $this->params['subtitle'] = $subtitle;

        return $this;
    }

    /**
     * The text element defines the text the user will get when
     * copying the selected result row with ⌘C or displaying large type with ⌘L.
     *
     * If these are not defined, you will inherit Alfred's standard behaviour
     * where the arg is copied to the Clipboard or used for Large Type.
     *
     * @param \Alfred\Workflows\Item\Text::TYPE_* $type
     * @param string $text
     * @return \Alfred\Workflows\Item
     */
    public function text($type, $text)
    {
        $this->mergeParam('text', Text::handle($type, $text));

        return $this;
    }

    /**
     * @param string $copy
     * @return \Alfred\Workflows\Item
     */
    public function copy($copy)
    {
        return $this->text(Text::TYPE_COPY, $copy);
    }

    /**
     * @param string $largetype
     * @return \Alfred\Workflows\Item
     */
    public function largetype($largetype)
    {
        return $this->text(Text::TYPE_LARGETYPE, $largetype);
    }

    /**
     * The mod element gives you control over how the modifier keys react.
     * You can now define the valid attribute to mark if the result is valid based
     * on the modifier selection and set a different arg to be passed out
     * if actioned with the modifier.
     *
     * @param \Alfred\Workflows\Item::MOD_* $mod
     * @param string $subtitle
     * @param string $arg
     * @param bool $valid
     * @return \Alfred\Workflows\Item
     */
    public function mod($mod, $subtitle, $arg, $valid = true)
    {
        $this->mergeParam('mods', Mod::handle($mod, $subtitle, $arg, $valid));

        return $this;
    }

    /**
     * @param string $subtitle
     * @param string $arg
     * @param bool $valid
     * @return \Alfred\Workflows\Item
     */
    public function cmd($subtitle, $arg, $valid = true)
    {
        return $this->mod(Mod::MOD_CMD, $subtitle, $arg, $valid);
    }

    /**
     * @param string $subtitle
     * @param string $arg
     * @param bool $valid
     * @return \Alfred\Workflows\Item
     */
    public function shift($subtitle, $arg, $valid = true)
    {
        return $this->mod(Mod::MOD_SHIFT, $subtitle, $arg, $valid);
    }

    /**
     * @param string $subtitle
     * @param string $arg
     * @param bool $valid
     * @return \Alfred\Workflows\Item
     */
    public function fn($subtitle, $arg, $valid = true)
    {
        return $this->mod(Mod::MOD_FN, $subtitle, $arg, $valid);
    }

    /**
     * @param string $subtitle
     * @param string $arg
     * @param bool $valid
     * @return \Alfred\Workflows\Item
     */
    public function ctrl($subtitle, $arg, $valid = true)
    {
        return $this->mod(Mod::MOD_CTRL, $subtitle, $arg, $valid);
    }

    /**
     * @param string $subtitle
     * @param string $arg
     * @param bool $valid
     * @return \Alfred\Workflows\Item
     */
    public function alt($subtitle, $arg, $valid = true)
    {
        return $this->mod(Mod::MOD_ALT, $subtitle, $arg, $valid);
    }

    /**
     * The match field enables you to define what Alfred matches against when
     * the workflow is set to "Alfred Filters Results". If match is present,
     * it fully replaces matching on the title property.
     *
     * Note that the match field is always treated as case insensitive, and
     * intelligently treated as diacritic insensitive. If the search query
     * contains a diacritic, the match becomes diacritic sensitive.
     *
     * @param string $match
     * @return \Alfred\Workflows\Item
     */
    public function match(string $match)
    {
        $this->params['match'] = $match;

        return $this;
    }

    /**
     * This is a unique identifier for the item which allows help
     * Alfred to learn about this item for subsequent sorting and
     * ordering of the user's actioned results.
     *
     * It is important that you use the same UID throughout subsequent
     * executions of your script to take advantage of Alfred's knowledge
     * and sorting. If you would like Alfred to always show the results
     * in the order you return them from your script, exclude the UID field.
     *
     * @param string $uid
     * @return \Alfred\Workflows\Item
     */
    public function uid(string $uid)
    {
        $this->params['uid'] = $uid;

        return $this;
    }

    /**
     * The title displayed in the result row. There are no options
     * for this element and it is essential that this element is populated.
     *
     * @param string $title
     * @return \Alfred\Workflows\Item
     */
    public function title(string $title)
    {
        $this->params['title'] = $title;

        return $this;
    }

    /**
     * A Quick Look URL which will be visible if the user uses the Quick Look
     * feature within Alfred (tapping shift, or ⌘Y). Note that quicklookurl will
     * also accept a file path, both absolute and relative to home using ~/.
     *
     * If absent, Alfred will attempt to use the arg as the quicklook URL.
     *
     * @param string $quicklookurl
     * @return \Alfred\Workflows\Item
     */
    public function quicklookurl(string $quicklookurl)
    {
        $this->params['quicklookurl'] = $quicklookurl;

        return $this;
    }

    /**
     * The argument which is passed through the workflow
     * to the connected output action.
     *
     * @param string|array $arg
     * @return \Alfred\Workflows\Item
     */
    public function arg(string|array $arg)
    {
        // TODO: IS THIS A CLASS? INVESTIGATE
        $this->params['arg'] = $arg;

        return $this;
    }

    /**
     * An optional but recommended string you can provide which is
     * populated into Alfred's search field if the user auto-complete's
     * the selected result (⇥ by default).
     *
     * If the item is set as "valid": false, the auto-complete text is
     * populated into Alfred's search field when the user actions the result.
     *
     * @param string $autocomplete
     * @return \Alfred\Workflows\Item
     */
    public function autocomplete(string $autocomplete)
    {
        $this->params['autocomplete'] = $autocomplete;

        return $this;
    }

    /**
     * @param string $key
     * @param array $value
     * @return void
     */
    protected function mergeParam(string $key, array $value)
    {
        if (array_key_exists($key, $this->params)) {
            $this->params[$key] = array_merge($this->params[$key], $value);
        } else {
            $this->params[$key] = $value;
        }
    }

    /**
     * Converts the results to an array structured for Alfred
     * @return array
     */
    public function toArray()
    {
        ksort($this->params);

        return $this->params;
    }

    public function __get($property)
    {
        if (array_key_exists($property, $this->params)) {
            return $this->params[$property];
        }

        return null;
    }
}
