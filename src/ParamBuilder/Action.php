<?php

namespace Alfred\Workflows\ParamBuilder;

use Alfred\Workflows\ItemParam\Action as ItemParamAction;
use Exception;

/**
 * @method static \Alfred\Workflows\ItemParam\Action text(string|array $text)
 * @method static \Alfred\Workflows\ItemParam\Action url(string|array $url)
 * @method static \Alfred\Workflows\ItemParam\Action file(string|array $file)
 * @method static \Alfred\Workflows\ItemParam\Action auto(string|array $auto)
 */

class Action
{
    /**
     * @throws Exception when method is not supported
     */
    public static function __callStatic(string $method, array $parameters): ItemParamAction
    {
        if (!in_array($method, ['text', 'url', 'file', 'auto'])) {
            throw new Exception('The ' . $method . ' is not supported in the Action object.');
        }

        $action = new ItemParamAction();

        $action->{$method}(...$parameters);

        return $action;
    }
}
