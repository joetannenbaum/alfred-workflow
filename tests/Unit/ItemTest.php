<?php

use Alfred\Workflows\ItemParam\Action as ItemParamAction;
use Alfred\Workflows\ItemParam\Mod as ItemParamMod;
use Alfred\Workflows\ItemParam\Type;
use Alfred\Workflows\ParamBuilder\Action;
use Alfred\Workflows\ParamBuilder\Mod;

it('will error when the title is missing from an item', function () {
    $this->workflow->item()->iconForFileType('icon.png');
    $this->workflow->output(false);
})->throws('Title missing from item: {"icon":{"path":"icon.png","type":"filetype"}}');

it('can add an item to the workflow', function () {
    $this->workflow->item()
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

    $this->expected = [
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
});

it('can add multiple items to the workflow', function () {
    $this->workflow->item()
        ->uid('THE ID')
        ->title('Item Title')
        ->subtitle('Item Subtitle')
        ->quickLookUrl('https://www.google.com')
        ->typeFile()
        ->argument('ARGUMENT')
        ->valid(false)
        ->icon('icon.png')
        ->mod(Mod::cmd()->subtitle('Do Something Different')->arg('something-different'))
        ->mod(Mod::shift()->subtitle('Another Different')->arg('another-different')->valid(false))
        ->copy('Please copy this')
        ->largetype('This will be huge')
        ->autocomplete('AutoComplete This');

    $this->workflow->item()
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

    $this->expected = [
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
});

it('can handle a file type via arguments', function () {
    $this->workflow->item()->title('Just a File')->type(Type::TYPE_FILE);

    $this->expected = [
        'items' => [
            [
                'title' => 'Just a File',
                'type'  => 'file',
            ],
        ],
    ];
});

it('can handle a file skipcheck via arguments', function () {
    $this->workflow->item()->title('Skipcheck')->type(Type::TYPE_FILE_SKIP_CHECK);

    $this->expected = [
        'items' => [
            [
                'title' => 'Skipcheck',
                'type'  => 'file:skipcheck',
            ],
        ],
    ];
});

it('can handle a file skipcheck via shortcut', function () {
    $this->workflow->item()->title('Skipcheck')->typeFileSkipExistenceCheck();

    $this->expected = [
        'items' => [
            [
                'title' => 'Skipcheck',
                'type'  => 'file:skipcheck',
            ],
        ],
    ];
});

it('can add mods via builder', function () {
    $this->workflow->item()
        ->title('Command Shift')
        ->mod(Mod::cmd()->subtitle('Hit Command')->arg('command-it')->valid(false))
        ->mod(Mod::shift()->subtitle('Hit Shift')->arg('shift-it')->valid(true));

    $this->expected = [
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
});

it('can pass a variable to the mod builder', function () {
    $this->workflow->item()
        ->title('Command Shift')
        ->mod(Mod::shift()->variable('testing', 'it out'));

    $this->expected = [
        'items' => [
            [
                'mods' => [
                    'shift' => [
                        'variables' => [
                            'testing' => 'it out',
                        ],
                    ],
                ],
                'title' => 'Command Shift',
            ],
        ],
    ];
});

it('can pass variables via array to the mod builder', function () {
    $this->workflow->item()
        ->title('Command Shift')
        ->mod(Mod::cmd()->variables(['testing' => 'it out']));

    $this->expected = [
        'items' => [
            [
                'mods' => [
                    'cmd' => [
                        'variables' => [
                            'testing' => 'it out',
                        ],
                    ],
                ],
                'title' => 'Command Shift',
            ],
        ],
    ];
});

it('can pass empty variables to the mod builder', function () {
    $this->workflow->item()
        ->title('Command Shift')
        ->mod(Mod::cmd()->variable(null))
        ->mod(Mod::shift()->variable('testing', 'it out'));

    $this->expected = [
        'items' => [
            [
                'mods' => [
                    'cmd' => [
                        'variables' => [],
                    ],
                    'shift' => [
                        'variables' => [
                            'testing' => 'it out',
                        ],
                    ],
                ],
                'title' => 'Command Shift',
            ],
        ],
    ];
});

it('can pass empty variables via array to the mod builder', function () {
    $this->workflow->item()
        ->title('Command Shift')
        ->mod(Mod::cmd()->variables([]))
        ->mod(Mod::shift()->variable('testing', 'it out'));

    $this->expected = [
        'items' => [
            [
                'mods' => [
                    'cmd' => [
                        'variables' => [],
                    ],
                    'shift' => [
                        'variables' => [
                            'testing' => 'it out',
                        ],
                    ],
                ],
                'title' => 'Command Shift',
            ],
        ],
    ];
});

it('can pass combinations of modifiers to an item', function () {
    $this->workflow->item()
                    ->title('Command + Shift')
                    ->mod(
                        [ItemParamMod::KEY_SHIFT, ItemParamMod::KEY_CMD],
                        function (ItemParamMod $mod) {
                            $mod->subtitle('Combo keys!');
                        }
                    );

    $this->expected = [
        'items' => [
            [
                'mods' => [
                    'shift+cmd' => [
                        'subtitle' => 'Combo keys!',
                    ],
                ],
                'title' => 'Command + Shift',
            ],
        ],
    ];
});

it('can add mods via shortcut callable', function () {
    $this->workflow->item()
        ->title('Command Shift')
        ->cmd(function (ItemParamMod $mod) {
            $mod->subtitle('Hit Command')->arg('command-it')->valid(false);
        })
        ->shift(function (ItemParamMod $mod) {
            $mod->subtitle('Hit Shift')->arg('shift-it')->valid(true);
        });

    $this->expected = [
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
});

it('can handle file icon via shortcut', function () {
    $this->workflow->item()
        ->title('Icon from File')
        ->iconForFilePath('icon.png');

    $this->expected = [
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
});

it('can handle file type via shortcut', function () {
    $this->workflow->item()
        ->title('Icon from File Type')
        ->iconForFileType('icon.png');

    $this->expected = [
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
});

it('will not override an existing argument when adding an action', function () {
    $this->workflow->item()
        ->title('Universal Action')
        ->arg('my_arg')
        ->action('This is the action arg.');

    $this->expected = [
        'items' => [
            [
                'action' => 'This is the action arg.',
                'arg' => 'my_arg',
                'title' => 'Universal Action',
            ],
        ],
    ];
});

it('will allow overriding placeholder action arg', function () {
    $this->workflow->item()
        ->title('Universal Action')
        ->action('This is the action arg.')
        ->arg('my_arg');

    $this->expected = [
        'items' => [
            [
                'action' => 'This is the action arg.',
                'arg' => 'my_arg',
                'title' => 'Universal Action',
            ],
        ],
    ];
});

it('can add a universal action from a string', function () {
    $this->workflow->item()
        ->title('Universal Action')
        ->action('This is the action arg.');

    $this->expected = [
        'items' => [
            [
                'action' => 'This is the action arg.',
                'arg' => 'action_arg',
                'title' => 'Universal Action',
            ],
        ],
    ];
});

it('can add a universal action from a callable', function () {
    $this->workflow->item()
        ->title('Universal Action')
        ->action(function (ItemParamAction $action) {
            $action->text('This is the action arg.');
        });

    $this->expected = [
        'items' => [
            [
                'action' => [
                    'text' => 'This is the action arg.',
                ],
                'arg' => 'action_arg',
                'title' => 'Universal Action',
            ],
        ],
    ];
});

it('can add a universal action from an array', function () {
    $this->workflow->item()
        ->title('Universal Action')
        ->action(['first', 'second', 'third']);

    $this->expected = [
        'items' => [
            [
                'action' => ['first', 'second', 'third'],
                'arg' => 'action_arg',
                'title' => 'Universal Action',
            ],
        ],
    ];
});

it('can add a universal action from an action object with string', function () {
    $this->workflow->item()
        ->title('Universal Action')
        ->action(
            Action::text('from an object!')
                ->url('https://joe.codes')
                ->file('~/Desktop/photo.jpg')
                ->auto('~/Desktop/document.pdf')
        );

    $this->expected = [
        'items' => [
            [
                'action' => [
                    'auto' => '~/Desktop/document.pdf',
                    'file' => '~/Desktop/photo.jpg',
                    'text' => 'from an object!',
                    'url' => 'https://joe.codes',
                ],
                'arg' => 'action_arg',
                'title' => 'Universal Action',
            ],
        ],
    ];
});

it('can add a universal action from an action object with array', function () {
    $this->workflow->item()
        ->title('Universal Action')
        ->action(
            Action::text(['first', 'second', 'third'])
                ->url('https://joe.codes')
                ->file('~/Desktop/photo.jpg')
                ->auto('~/Desktop/document.pdf')
        );

    $this->expected = [
        'items' => [
            [
                'action' => [
                    'auto' => '~/Desktop/document.pdf',
                    'file' => '~/Desktop/photo.jpg',
                    'text' => ['first', 'second', 'third'],
                    'url' => 'https://joe.codes',
                ],
                'arg' => 'action_arg',
                'title' => 'Universal Action',
            ],
        ],
    ];
});

it('can add variables to an item', function () {
    $this->workflow->item()
        ->title('Item Title')
        ->variable('something', 'also this');

    $this->expected = [
        'items' => [
            [
                'title'        => 'Item Title',
                'variables' => [
                    'something' => 'also this',
                ]
            ],
        ],
    ];
});

it('can add an array of variables to an item', function () {
    $this->workflow->item()
        ->title('Item Title')
        ->variable(['something' => 'also this']);

    $this->expected = [
        'items' => [
            [
                'title'        => 'Item Title',
                'variables' => [
                    'something' => 'also this',
                ]
            ],
        ],
    ];
});

it('can add an array of variables to an item using plural method', function () {
    $this->workflow->item()
        ->title('Item Title')
        ->variables(['something' => 'also this']);

    $this->expected = [
        'items' => [
            [
                'title'        => 'Item Title',
                'variables' => [
                    'something' => 'also this',
                ]
            ],
        ],
    ];
});
