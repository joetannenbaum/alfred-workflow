<?php

it('can rerun the workflow', function () {
    $this->workflow->item()->title('Re-run please');

    $this->workflow->rerun(4);

    $this->expected = [
        'items' => [
            [
                'title' => 'Re-run please',
            ],
        ],
        'rerun' => 4,
    ];
});

it('will error if the rerun value is out of range', function () {
    $this->workflow->item()->title('Re-run please');
    $this->workflow->rerun(10);
    $this->workflow->output(false);
})->throws('Re-run $seconds must be between 0.1 and 5.0 seconds');

it('can_add_variables', function () {
    $this->workflow->variable('fruit', 'apple')
        ->variable('vegetables', 'carrots');

    $this->workflow->item()->title('Item Title');

    $this->expected = [
        'items' => [
            [
                'title' => 'Item Title',
            ],
        ],
        'variables' => [
            'fruit' => 'apple',
            'vegetables' => 'carrots'
        ]
    ];
});

it('can skip knowledge', function () {
    $this->workflow->skipKnowledge();

    $this->workflow->item()->title('Item Title');

    $this->expected = [
        'items' => [
            [
                'title'        => 'Item Title',
            ],
        ],
        'skipknowledge' => true,
    ];
});
