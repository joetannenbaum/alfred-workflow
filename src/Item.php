<?php

namespace Alfred\Workflows;

use Alfred\Workflows\ItemParam\Action;
use Alfred\Workflows\ItemParam\HasAnIcon;
use Alfred\Workflows\ItemParam\HasArguments;
use Alfred\Workflows\ItemParam\HasParams;
use Alfred\Workflows\ItemParam\HasSubtitle;
use Alfred\Workflows\ItemParam\HasValidity;
use Alfred\Workflows\ItemParam\HasVariables;
use Alfred\Workflows\ItemParam\Mod;
use Alfred\Workflows\ItemParam\Text;
use Alfred\Workflows\ItemParam\Type;
use Alfred\Workflows\ParamBuilder\Mod as ParamBuilderMod;
use Exception;

class Item
{
    use HasAnIcon;
    use HasParams;
    use HasValidity;
    use HasVariables;
    use HasSubtitle;
    use HasArguments;

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
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     *
     * @param string $type see \Alfred\Workflows\Item\Type::TYPE_*
     *
     * @throws Exception if $type is invalid
     */
    public function type(string $type): self
    {
        $this->params['type'] = Type::handle($type);

        return $this;
    }

    public function typeFile(): self
    {
        return $this->type(Type::TYPE_FILE);
    }

    public function typeFileSkipExistenceCheck(): self
    {
        return $this->type(Type::TYPE_FILE_SKIP_CHECK);
    }

    /**
     * The text element defines the text the user will get when
     * copying the selected result row with ⌘C or displaying large type with ⌘L.
     *
     * If these are not defined, you will inherit Alfred's standard behaviour
     * where the arg is copied to the Clipboard or used for Large Type.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     *
     * @param string $type see \Alfred\Workflows\Item\Text::TYPE_*
     * @param string $text
     *
     * @throws Exception if $type is invalid
     */
    public function text(string $type, string $text): self
    {
        $this->mergeParam('text', Text::handle($type, $text));

        return $this;
    }

    /**
     * Defines the text the user will get when copying the
     * selected result row with ⌘C.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function copy(string $copy): self
    {
        return $this->text(Text::TYPE_COPY, $copy);
    }

    /**
     * Defines the text the user will get when displaying
     * large type with ⌘L.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function largeType(string $largeType): self
    {
        return $this->text(Text::TYPE_LARGE_TYPE, $largeType);
    }

    /**
     * The mod element gives you control over how the modifier keys react.
     * You can now define the valid attribute to mark if the result is valid based
     * on the modifier selection and set a different arg to be passed out
     * if actioned with the modifier.
     *
     * @param Mod|array $mod
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function mod($mod, ?callable $fn = null): self
    {
        if (is_array($mod)) {
            return $this->modViaCallable($mod, $fn);
        }

        $this->mergeParam('mods', $mod->toArray());

        return $this;
    }

    /**
     * Define a cmd key mod.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function cmd(callable $fn): self
    {
        return $this->modViaCallable(Mod::KEY_CMD, $fn);
    }

    /**
     * Define a shift key mod.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function shift(callable $fn): self
    {
        return $this->modViaCallable(Mod::KEY_SHIFT, $fn);
    }

    /**
     * Define a alt key mod.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function alt(callable $fn): self
    {
        return $this->modViaCallable(Mod::KEY_ALT, $fn);
    }

    /**
     * Define a ctrl key mod.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function ctrl(callable $fn): self
    {
        return $this->modViaCallable(Mod::KEY_CTRL, $fn);
    }

    /**
     * Define a fn key mod.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function fn(callable $fn): self
    {
        return $this->modViaCallable(Mod::KEY_FN, $fn);
    }

    /**
     * @param array|string $key
     */
    protected function modViaCallable($key, callable $fn): self
    {
        if (is_array($key)) {
            $mod = ParamBuilderMod::combo($key);
        } else {
            $mod = ParamBuilderMod::$key();
        }


        $fn($mod);

        return $this->mod($mod);
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
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function match(string $match): self
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
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function uid(string $uid): self
    {
        $this->params['uid'] = $uid;

        return $this;
    }

    /**
     * The title displayed in the result row. There are no options
     * for this element and it is essential that this element is populated.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function title(string $title): self
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
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function quickLookUrl(string $url): self
    {
        $this->params['quicklookurl'] = $url;

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
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function autocomplete(string $autocomplete): self
    {
        $this->params['autocomplete'] = $autocomplete;

        return $this;
    }

    /**
     * New in Alfred 4.5
     *
     * This element defines the Universal Action items used when actioning the result,
     * and overrides arg being used for actioning. The action key can take a string or
     * array for simple types', and the content type will automatically be derived by
     * Alfred to file, url or text.
     *
     * If you pass in \Alfred\Workflows\ItemParam\Action or callable, you are specifying
     * the content type for Alfred.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     *
     * @param string|array|Action|callable $action
     *
     * @throws Exception if $action param is of an unknown type
     */
    public function action($action): self
    {
        if ($this->resolveAction($action)) {
            // Universal actions won't work without an argument, just set one if we don't have one already
            $this->setArgumentIfEmpty('action_arg');

            return $this;
        }

        throw new Exception('Unknown `action` value, should be a string, array, or instance of \Alfred\Workflows\ItemParam\Action.');
    }

    /**
     * @param string|array|Action|callable $action
     */
    protected function resolveAction($action): bool
    {
        if ($action instanceof Action) {
            $this->params['action'] = $action->toArray();
            return true;
        }

        if (is_string($action) || is_numeric($action) || is_array($action)) {
            $this->params['action'] = $action;
            return true;
        }

        if (is_callable($action)) {
            $obj = new Action();
            $action($obj);
            $this->params['action'] = $obj->toArray();
            return true;
        }

        return false;
    }

    public function __get(string $property)
    {
        if (array_key_exists($property, $this->params)) {
            return $this->params[$property];
        }

        return null;
    }
}
