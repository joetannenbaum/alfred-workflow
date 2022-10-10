<?php

namespace Alfred\Workflows;

use Alfred\Workflows\ItemParam\HasArguments;
use Alfred\Workflows\ItemParam\HasConfig;
use Alfred\Workflows\ItemParam\HasVariables;

class RunScript
{
    use HasVariables;
    use HasArguments;
    use HasConfig;

    public function output(bool $echo = true): string
    {
        $json = json_encode([
            'alfredworkflow' => $this->params,
        ]);

        if ($echo) {
            echo $json;
        }

        return $json;
    }
}
