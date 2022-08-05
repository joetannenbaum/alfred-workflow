<?php

use Alfred\Workflows\Workflow;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

class TestCase extends FrameworkTestCase
{
    /** @test */
    public function it_will_error_when_title_is_missing_from_item()
    {
        $this->expectExceptionMessage('Title missing from item: {"icon":{"path":"icon.png","type":"filetype"}}');

        $workflow = new Workflow;
        $workflow->item()->iconFromFileType('icon.png');
        $workflow->output(false);
    }

    /** @test */
    public function it_can_add_an_item()
    {
        $workflow = new Workflow;

        $workflow->item()
            ->uid('THE ID')
            ->title('Item Title')
            ->subtitle('Item Subtitle')
            ->quicklookurl('https://www.google.com')
            ->type('file')
            ->arg('ARGUMENT')
            ->valid(false)
            ->icon('icon.png')
            ->mod('cmd', 'Do Something Different', 'something-different')
            ->mod('shift', 'Another Different', 'another-different', false)
            ->copy('Please copy this')
            ->largetype('This will be huge')
            ->autocomplete('AutoComplete This');

        $expected = [
            'items' => [
                [
                    'arg'          => 'ARGUMENT',
                    'autocomplete' => 'AutoComplete This',
                    'icon'         => [
                        'path' => 'icon.png',
                    ],
                    'mods' => [
                        'cmd' => [
                            'subtitle' => 'Do Something Different',
                            'arg'      => 'something-different',
                            'valid'    => true,
                        ],
                        'shift' => [
                            'subtitle' => 'Another Different',
                            'arg'      => 'another-different',
                            'valid'    => false,
                        ],
                    ],
                    'quicklookurl' => 'https://www.google.com',
                    'subtitle'     => 'Item Subtitle',
                    'text'         => [
                        'copy'      => 'Please copy this',
                        'largetype' => 'This will be huge',
                    ],
                    'title'        => 'Item Title',
                    'type'         => 'file',
                    'uid'          => 'THE ID',
                    'valid'        => false,
                ],
            ],
        ];

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_add_multiple_items()
    {
        $workflow = new Workflow;

        $workflow->item()
            ->uid('THE ID')
            ->title('Item Title')
            ->subtitle('Item Subtitle')
            ->quicklookurl('https://www.google.com')
            ->type('file')
            ->arg('ARGUMENT')
            ->valid(false)
            ->icon('icon.png')
            ->mod('cmd', 'Do Something Different', 'something-different')
            ->mod('shift', 'Another Different', 'another-different', false)
            ->copy('Please copy this')
            ->largetype('This will be huge')
            ->autocomplete('AutoComplete This');

        $workflow->item()
            ->uid('THE ID 2')
            ->title('Item Title 2')
            ->subtitle('Item Subtitle 2')
            ->quicklookurl('https://www.google.com/2')
            ->type('file')
            ->arg('ARGUMENT 2')
            ->valid(true)
            ->icon('icon2.png')
            ->mod('cmd', 'Do Something Different 2', 'something-different 2')
            ->mod('shift', 'Another Different 2', 'another-different 2', false)
            ->copy('Please copy this 2')
            ->largetype('This will be huge 2')
            ->autocomplete('AutoComplete This 2');

        $expected = [
            'items' => [
                [
                    'arg'          => 'ARGUMENT',
                    'autocomplete' => 'AutoComplete This',
                    'icon'         => [
                        'path' => 'icon.png',
                    ],
                    'mods' => [
                        'cmd' => [
                            'subtitle' => 'Do Something Different',
                            'arg'      => 'something-different',
                            'valid'    => true,
                        ],
                        'shift' => [
                            'subtitle' => 'Another Different',
                            'arg'      => 'another-different',
                            'valid'    => false,
                        ],
                    ],
                    'quicklookurl' => 'https://www.google.com',
                    'subtitle'     => 'Item Subtitle',
                    'text'         => [
                        'copy'      => 'Please copy this',
                        'largetype' => 'This will be huge',
                    ],
                    'title'        => 'Item Title',
                    'type'         => 'file',
                    'uid'          => 'THE ID',
                    'valid'        => false,
                ],
                [
                    'arg'          => 'ARGUMENT 2',
                    'autocomplete' => 'AutoComplete This 2',
                    'icon'         => [
                        'path' => 'icon2.png',
                    ],
                    'mods' => [
                        'cmd' => [
                            'subtitle' => 'Do Something Different 2',
                            'arg'      => 'something-different 2',
                            'valid'    => true,
                        ],
                        'shift' => [
                            'subtitle' => 'Another Different 2',
                            'arg'      => 'another-different 2',
                            'valid'    => false,
                        ],
                    ],
                    'quicklookurl' => 'https://www.google.com/2',
                    'subtitle'     => 'Item Subtitle 2',
                    'text'         => [
                        'copy'      => 'Please copy this 2',
                        'largetype' => 'This will be huge 2',
                    ],
                    'title'        => 'Item Title 2',
                    'type'         => 'file',
                    'uid'          => 'THE ID 2',
                    'valid'        => true,
                ],
            ],
        ];

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_handle_a_file_skipcheck_via_arguments()
    {
        $workflow = new Workflow;

        $workflow->item()->title('Skipcheck')->type('file', false);

        $expected = [
            'items' => [
                [
                    'title' => 'Skipcheck',
                    'type'  => 'file:skipcheck',
                ],
            ],
        ];

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_add_mods_via_shortcuts()
    {
        $workflow = new Workflow;

        $workflow->item()
            ->title('Command Shift')
            ->cmd('Hit Command', 'command-it', false)
            ->shift('Hit Shift', 'shift-it', true);

        $expected = [
            'items' => [
                [
                    'mods' => [
                        'cmd' => [
                            'subtitle' => 'Hit Command',
                            'arg'      => 'command-it',
                            'valid'    => false,
                        ],
                        'shift' => [
                            'subtitle' => 'Hit Shift',
                            'arg'      => 'shift-it',
                            'valid'    => true,
                        ],
                    ],
                    'title' => 'Command Shift',
                ],
            ],
        ];

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_handle_file_icon_via_shortcut()
    {
        $workflow = new Workflow;

        $workflow->item()
            ->title('Icon from File')
            ->iconFromFile('icon.png');

        $expected = [
            'items' => [
                [
                    'icon' => [
                        'path' => 'icon.png',
                        'type' => 'fileicon',
                    ],
                    'title' => 'Icon from File',
                ],
            ],
        ];

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_handle_file_type_via_shortcut()
    {
        $workflow = new Workflow;

        $workflow->item()
            ->title('Icon from File Type')
            ->iconFromFileType('icon.png');

        $expected = [
            'items' => [
                [
                    'icon' => [
                        'path' => 'icon.png',
                        'type' => 'filetype',
                    ],
                    'title' => 'Icon from File Type',
                ],
            ],
        ];

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_sort_items_by_defaults()
    {
        $workflow = new Workflow;

        $workflow->item()
            ->uid('THE ID')
            ->title('Item Title')
            ->subtitle('Item Subtitle');

        $workflow->item()
            ->uid('THE ID 2')
            ->title('Item Title 2')
            ->subtitle('Item Subtitle 2');

        $expected = [
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

        $this->assertSame(json_encode($expected), $workflow->sortItems()->output(false));
    }

    /** @test */
    public function it_can_sort_items_desc()
    {
        $workflow = new Workflow;

        $workflow->item()
            ->uid('THE ID')
            ->title('Item Title')
            ->subtitle('Item Subtitle');

        $workflow->item()
            ->uid('THE ID 2')
            ->title('Item Title 2')
            ->subtitle('Item Subtitle 2');

        $expected = [
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

        $this->assertSame(json_encode($expected), $workflow->sortItems('desc')->output(false));
    }

    /** @test */
    public function it_can_sort_items_by_field()
    {
        $workflow = new Workflow;

        $workflow->item()
            ->uid('456')
            ->title('Item Title')
            ->subtitle('Item Subtitle');

        $workflow->item()
            ->uid('123')
            ->title('Item Title 2')
            ->subtitle('Item Subtitle 2');

        $expected = [
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

        $this->assertSame(json_encode($expected), $workflow->sortItems('asc', 'uid')->output(false));
    }

    /** @test */
    public function it_can_filter_items()
    {
        $workflow = new Workflow;

        $workflow->item()
            ->uid('THE ID')
            ->title('Item Title')
            ->subtitle('Item Subtitle');

        $workflow->item()
            ->uid('THE ID 2')
            ->title('Item Title 2')
            ->subtitle('Item Subtitle 2');

        $expected = [
            'items' => [
                [
                    'subtitle'     => 'Item Subtitle 2',
                    'title'        => 'Item Title 2',
                    'uid'          => 'THE ID 2',
                ],
            ],
        ];

        $this->assertSame(json_encode($expected), $workflow->filterItems(2)->output(false));
    }

    /** @test */
    public function it_can_filter_items_by_a_different_key()
    {
        $workflow = new Workflow;

        $workflow->item()
            ->uid('THE ID')
            ->title('Item Title')
            ->subtitle('Item Subtitle');

        $workflow->item()
            ->uid('THE ID 2')
            ->title('Item Title 2')
            ->subtitle('Item Subtitle 2');

        $expected = [
            'items' => [
                [
                    'subtitle'     => 'Item Subtitle 2',
                    'title'        => 'Item Title 2',
                    'uid'          => 'THE ID 2',
                ],
            ],
        ];

        $this->assertSame(json_encode($expected), $workflow->filterItems('ID 2', 'uid')->output(false));
    }

    /** @test */
    public function it_can_add_variables()
    {
        $workflow = new Workflow;

        $workflow->variable('fruit', 'apple')
            ->variable('vegetables', 'carrots');

        $workflow->item()
            ->uid('THE ID')
            ->title('Item Title')
            ->subtitle('Item Subtitle')
            ->quicklookurl('https://www.google.com')
            ->type('file')
            ->arg('ARGUMENT')
            ->valid(false)
            ->icon('icon.png')
            ->mod('cmd', 'Do Something Different', 'something-different')
            ->mod('shift', 'Another Different', 'another-different', false)
            ->copy('Please copy this')
            ->largetype('This will be huge')
            ->autocomplete('AutoComplete This');

        $expected = [
            'items' => [
                [
                    'arg'          => 'ARGUMENT',
                    'autocomplete' => 'AutoComplete This',
                    'icon'         => [
                        'path' => 'icon.png',
                    ],
                    'mods' => [
                        'cmd' => [
                            'subtitle' => 'Do Something Different',
                            'arg'      => 'something-different',
                            'valid'    => true,
                        ],
                        'shift' => [
                            'subtitle' => 'Another Different',
                            'arg'      => 'another-different',
                            'valid'    => false,
                        ],
                    ],
                    'quicklookurl' => 'https://www.google.com',
                    'subtitle'     => 'Item Subtitle',
                    'text'         => [
                        'copy'      => 'Please copy this',
                        'largetype' => 'This will be huge',
                    ],
                    'title'        => 'Item Title',
                    'type'         => 'file',
                    'uid'          => 'THE ID',
                    'valid'        => false,
                ],
            ],
            'variables' => [
                'fruit' => 'apple',
                'vegetables' => 'carrots'
            ]
        ];

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }
}
