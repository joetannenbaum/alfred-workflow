<?php

use Alfred\Workflows\Items;

it('can sort items by defaults', function () {
    $this->workflow->item()
        ->uid('THE ID')
        ->title('Item Title')
        ->subtitle('Item Subtitle');

    $this->workflow->item()
        ->uid('THE ID 2')
        ->title('Item Title 2')
        ->subtitle('Item Subtitle 2');

    $this->expected = [
        'items' => [
            [
                'subtitle'     => 'Item Subtitle',
                'title'        => 'Item Title',
                'uid'          => 'THE ID',
            ],
            [
                'subtitle'     => 'Item Subtitle 2',
                'title'        => 'Item Title 2',
                'uid'          => 'THE ID 2',
            ],
        ],
    ];

    $this->workflow->items()->sort();
});

it('can sort items desc', function () {
    $this->workflow->item()
        ->uid('THE ID')
        ->title('Item Title')
        ->subtitle('Item Subtitle');

    $this->workflow->item()
        ->uid('THE ID 2')
        ->title('Item Title 2')
        ->subtitle('Item Subtitle 2');

    $this->expected = [
        'items' => [
            [
                'subtitle'     => 'Item Subtitle 2',
                'title'        => 'Item Title 2',
                'uid'          => 'THE ID 2',
            ],
            [
                'subtitle'     => 'Item Subtitle',
                'title'        => 'Item Title',
                'uid'          => 'THE ID',
            ],
        ],
    ];

    $this->workflow->items()->sort('title', Items::SORT_DESC);
});

it('can sort items by field', function () {
    $this->workflow->item()
        ->uid('456')
        ->title('Item Title')
        ->subtitle('Item Subtitle');

    $this->workflow->item()
        ->uid('123')
        ->title('Item Title 2')
        ->subtitle('Item Subtitle 2');

    $this->expected = [
        'items' => [
            [
                'subtitle'     => 'Item Subtitle 2',
                'title'        => 'Item Title 2',
                'uid'          => '123',
            ],
            [
                'subtitle'     => 'Item Subtitle',
                'title'        => 'Item Title',
                'uid'          => '456',
            ],
        ],
    ];

    $this->workflow->items()->sort('uid', Items::SORT_ASC);
});

it('can sort items via a custom function', function () {
    $this->workflow->item()
        ->uid('123')
        ->title('Item Title 2')
        ->subtitle('Item Subtitle 2');

    $this->workflow->item()
        ->uid('456')
        ->title('Item Title')
        ->subtitle('Item Subtitle');

    $this->expected = [
        'items' => [
            [
                'subtitle'     => 'Item Subtitle',
                'title'        => 'Item Title',
                'uid'          => '456',
            ],
            [
                'subtitle'     => 'Item Subtitle 2',
                'title'        => 'Item Title 2',
                'uid'          => '123',
            ],
        ],
    ];

    $this->workflow->items()->sort(function ($a, $b) {
        return $a->subtitle > $b->subtitle ? 1 : -1;
    });
});

it('can filter items', function () {
    $this->workflow->item()
        ->uid('THE ID')
        ->title('Item Title')
        ->subtitle('Item Subtitle');

    $this->workflow->item()
        ->uid('THE ID 2')
        ->title('Item Title 2')
        ->subtitle('Item Subtitle 2');

    $this->expected = [
        'items' => [
            [
                'subtitle'     => 'Item Subtitle 2',
                'title'        => 'Item Title 2',
                'uid'          => 'THE ID 2',
            ],
        ],
    ];

    $this->workflow->items()->filter(2);
});

it('can filter items by a different key', function () {
    $this->workflow->item()
        ->uid('THE ID')
        ->title('Item Title')
        ->subtitle('Item Subtitle');

    $this->workflow->item()
        ->uid('THE ID 2')
        ->title('Item Title 2')
        ->subtitle('Item Subtitle 2');

    $this->expected = [
        'items' => [
            [
                'subtitle'     => 'Item Subtitle 2',
                'title'        => 'Item Title 2',
                'uid'          => 'THE ID 2',
            ],
        ],
    ];

    $this->workflow->items()->filter('ID 2', 'uid');
});

it('can filter using a custom function', function () {
    $this->workflow->item()
        ->uid('THE ID')
        ->title('Item Title')
        ->subtitle('Item Subtitle');

    $this->workflow->item()
        ->uid('THE ID 2')
        ->title('Item Title 2')
        ->subtitle('Item Subtitle 2');

    $this->expected = [
        'items' => [
            [
                'subtitle'     => 'Item Subtitle 2',
                'title'        => 'Item Title 2',
                'uid'          => 'THE ID 2',
            ],
        ],
    ];

    $this->workflow->items()->filter(function ($item) {
        return strstr($item->subtitle, '2');
    });
});
