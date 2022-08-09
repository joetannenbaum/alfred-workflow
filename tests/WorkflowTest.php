<?php

use Alfred\Workflows\ItemParam\Action as ItemParamAction;
use Alfred\Workflows\ItemParam\Mod as ItemParamMod;
use Alfred\Workflows\ItemParam\Type;
use Alfred\Workflows\Items;
use Alfred\Workflows\ParamBuilder\Action;
use Alfred\Workflows\ParamBuilder\Mod;
use Alfred\Workflows\Workflow;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

class WorkflowTest extends FrameworkTestCase
{
    /** @test */
    public function it_will_error_when_title_is_missing_from_item()
    {
        $this->expectExceptionMessage('Title missing from item: {"icon":{"path":"icon.png","type":"filetype"}}');

        $workflow = new Workflow();
        $workflow->item()->iconFromFileType('icon.png');
        $workflow->output(false);
    }

    /** @test */
    public function it_can_add_an_item()
    {
        $workflow = new Workflow();

        $workflow->item()
            ->uid('THE ID')
            ->title('Item Title')
            ->subtitle('Item Subtitle')
            ->quickLookUrl('https://www.google.com')
            ->typeFile()
            ->arg('ARGUMENT')
            ->invalid()
            ->icon('icon.png')
            ->mod(Mod::cmd()->subtitle('Do Something Different')->arg('something-different'))
            ->mod(Mod::shift()->subtitle('Another Different')->arg('another-different')->invalid())
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
                            'arg'      => 'something-different',
                            'subtitle' => 'Do Something Different',
                        ],
                        'shift' => [
                            'arg'      => 'another-different',
                            'subtitle' => 'Another Different',
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
        $workflow = new Workflow();

        $workflow->item()
            ->uid('THE ID')
            ->title('Item Title')
            ->subtitle('Item Subtitle')
            ->quickLookUrl('https://www.google.com')
            ->typeFile()
            ->arg('ARGUMENT')
            ->valid(false)
            ->icon('icon.png')
            ->mod(Mod::cmd()->subtitle('Do Something Different')->arg('something-different'))
            ->mod(Mod::shift()->subtitle('Another Different')->arg('another-different')->valid(false))
            ->copy('Please copy this')
            ->largetype('This will be huge')
            ->autocomplete('AutoComplete This');

        $workflow->item()
            ->uid('THE ID 2')
            ->title('Item Title 2')
            ->subtitle('Item Subtitle 2')
            ->quickLookUrl('https://www.google.com/2')
            ->typeFile()
            ->arg('ARGUMENT 2')
            ->valid(true)
            ->icon('icon2.png')
            ->mod(Mod::cmd()->subtitle('Do Something Different 2')->arg('something-different 2'))
            ->mod(Mod::shift()->subtitle('Another Different 2')->arg('another-different 2')->valid(false))
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
                            'arg'      => 'something-different',
                            'subtitle' => 'Do Something Different',
                        ],
                        'shift' => [
                            'arg'      => 'another-different',
                            'subtitle' => 'Another Different',
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
                            'arg'      => 'something-different 2',
                            'subtitle' => 'Do Something Different 2',
                        ],
                        'shift' => [
                            'arg'      => 'another-different 2',
                            'subtitle' => 'Another Different 2',
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
    public function it_can_handle_a_file_type_via_arguments()
    {
        $workflow = new Workflow();

        $workflow->item()->title('Just a File')->type(Type::TYPE_FILE);

        $expected = [
            'items' => [
                [
                    'title' => 'Just a File',
                    'type'  => 'file',
                ],
            ],
        ];

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_handle_a_file_skipcheck_via_arguments()
    {
        $workflow = new Workflow();

        $workflow->item()->title('Skipcheck')->type(Type::TYPE_FILE_SKIP_CHECK);

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
    public function it_can_handle_a_file_skipcheck_via_shortcut()
    {
        $workflow = new Workflow();

        $workflow->item()->title('Skipcheck')->typeFileSkipExistenceCheck();

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
    public function it_can_add_mods_via_builder()
    {
        $workflow = new Workflow();

        $workflow->item()
            ->title('Command Shift')
            ->mod(Mod::cmd()->subtitle('Hit Command')->arg('command-it')->valid(false))
            ->mod(Mod::shift()->subtitle('Hit Shift')->arg('shift-it')->valid(true));

        $expected = [
            'items' => [
                [
                    'mods' => [
                        'cmd' => [
                            'arg'      => 'command-it',
                            'subtitle' => 'Hit Command',
                            'valid'    => false,
                        ],
                        'shift' => [
                            'arg'      => 'shift-it',
                            'subtitle' => 'Hit Shift',
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
    public function it_can_add_mods_via_shortcut_callable()
    {
        $workflow = new Workflow();

        $workflow->item()
            ->title('Command Shift')
            ->cmd(function (ItemParamMod $mod) {
                $mod->subtitle('Hit Command')->arg('command-it')->valid(false);
            })
            ->shift(function (ItemParamMod $mod) {
                $mod->subtitle('Hit Shift')->arg('shift-it')->valid(true);
            });

        $expected = [
            'items' => [
                [
                    'mods' => [
                        'cmd' => [
                            'arg'      => 'command-it',
                            'subtitle' => 'Hit Command',
                            'valid'    => false,
                        ],
                        'shift' => [
                            'arg'      => 'shift-it',
                            'subtitle' => 'Hit Shift',
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
        $workflow = new Workflow();

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
        $workflow = new Workflow();

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
    public function it_can_add_a_universal_action_from_a_string()
    {
        $workflow = new Workflow();

        $workflow->item()
            ->title('Universal Action')
            ->action('This is the action arg.');

        $expected = [
            'items' => [
                [
                    'action' => 'This is the action arg.',
                    'title' => 'Universal Action',
                ],
            ],
        ];

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_add_a_universal_action_from_a_callable()
    {
        $workflow = new Workflow();

        $workflow->item()
            ->title('Universal Action')
            ->action(function (ItemParamAction $action) {
                $action->text('This is the action arg.');
            });

        $expected = [
            'items' => [
                [
                    'action' => [
                        'text' => 'This is the action arg.',
                    ],
                    'title' => 'Universal Action',
                ],
            ],
        ];

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_add_a_universal_action_from_an_array()
    {
        $workflow = new Workflow();

        $workflow->item()
            ->title('Universal Action')
            ->action(['first', 'second', 'third']);

        $expected = [
            'items' => [
                [
                    'action' => ['first', 'second', 'third'],
                    'title' => 'Universal Action',
                ],
            ],
        ];

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_add_a_universal_action_from_an_action_object_with_string()
    {
        $workflow = new Workflow();

        $workflow->item()
            ->title('Universal Action')
            ->action(
                Action::text('from an object!')
                    ->url('https://joe.codes')
                    ->file('~/Desktop/photo.jpg')
                    ->auto('~/Desktop/document.pdf')
            );

        $expected = [
            'items' => [
                [
                    'action' => [
                        'auto' => '~/Desktop/document.pdf',
                        'file' => '~/Desktop/photo.jpg',
                        'text' => 'from an object!',
                        'url' => 'https://joe.codes',
                    ],
                    'title' => 'Universal Action',
                ],
            ],
        ];

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_add_a_universal_action_from_an_action_object_with_array()
    {
        $workflow = new Workflow();

        $workflow->item()
            ->title('Universal Action')
            ->action(
                Action::text(['first', 'second', 'third'])
                    ->url('https://joe.codes')
                    ->file('~/Desktop/photo.jpg')
                    ->auto('~/Desktop/document.pdf')
            );

        $expected = [
            'items' => [
                [
                    'action' => [
                        'auto' => '~/Desktop/document.pdf',
                        'file' => '~/Desktop/photo.jpg',
                        'text' => ['first', 'second', 'third'],
                        'url' => 'https://joe.codes',
                    ],
                    'title' => 'Universal Action',
                ],
            ],
        ];

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_rerun_workflow()
    {
        $workflow = new Workflow();

        $workflow->item()->title('Re-run please');

        $workflow->rerun(4);

        $expected = [
            'items' => [
                [
                    'title' => 'Re-run please',
                ],
            ],
            'rerun' => 4,
        ];

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_will_error_if_rerun_is_out_of_range()
    {
        $this->expectExceptionMessage('Re-run $seconds must be between 0.1 and 5.0 seconds');
        $workflow = new Workflow();
        $workflow->item()->title('Re-run please');
        $workflow->rerun(10);
        $workflow->output(false);
    }

    /** @test */
    public function it_can_sort_items_by_defaults()
    {
        $workflow = new Workflow();

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

        $workflow->items()->sort();

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_sort_items_desc()
    {
        $workflow = new Workflow();

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

        $workflow->items()->sort(Items::SORT_DESC);

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_sort_items_by_field()
    {
        $workflow = new Workflow();

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

        $workflow->items()->sort(Items::SORT_ASC, 'uid');

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_filter_items()
    {
        $workflow = new Workflow();

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

        $workflow->items()->filter(2);

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_filter_items_by_a_different_key()
    {
        $workflow = new Workflow();

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

        $workflow->items()->filter('ID 2', 'uid');

        $this->assertSame(json_encode($expected), $workflow->output(false));
    }

    /** @test */
    public function it_can_add_variables()
    {
        $workflow = new Workflow();

        $workflow->variable('fruit', 'apple')
            ->variable('vegetables', 'carrots');

        $workflow->item()
            ->uid('THE ID')
            ->title('Item Title')
            ->subtitle('Item Subtitle')
            ->quickLookUrl('https://www.google.com')
            ->typeFile()
            ->arg('ARGUMENT')
            ->valid(false)
            ->icon('icon.png')
            ->mod(Mod::cmd()->subtitle('Do Something Different')->arg('something-different'))
            ->mod(Mod::shift()->subtitle('Another Different')->arg('another-different')->valid(false))
            ->copy('Please copy this')
            ->largeType('This will be huge')
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
                            'arg'      => 'something-different',
                            'subtitle' => 'Do Something Different',
                        ],
                        'shift' => [
                            'arg'      => 'another-different',
                            'subtitle' => 'Another Different',
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
