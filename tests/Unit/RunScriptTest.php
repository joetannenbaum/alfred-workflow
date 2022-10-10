<?php

it('can add variables from a run script', function () {
    $output = $this->workflow->setFromRunScript()->variables([
        'color' => 'green',
    ])->output(false);

    expect($output)->toBe(json_encode(['alfredworkflow' => ['variables' => ['color' => 'green']]]));
});

it('can add an argument from a run script', function () {
    $output = $this->workflow->setFromRunScript()->arg('just passing through')->output(false);

    expect($output)->toBe(json_encode(['alfredworkflow' => ['arg' => 'just passing through']]));
});

it('can add a config from a run script', function () {
    $output = $this->workflow->setFromRunScript()->config([
        'url' => '{query}',
    ])->output(false);

    expect($output)->toBe(json_encode(['alfredworkflow' => ['config' => ['url' => '{query}']]]));
});

it('can add a combination from a run script', function () {
    $output = $this->workflow->setFromRunScript()->config([
        'url' => '{query}',
    ])->variables([
        'color' => 'green',
    ])->arg('just passing through')->output(false);

    expect($output)->toBe(json_encode(['alfredworkflow' => [
        'config' => ['url' => '{query}'],
        'variables' => ['color' => 'green'],
        'arg' => 'just passing through',
    ]]));
});
