<?php

declare(strict_types=1);

return [
    'preset' => 'default',
    'exclude' => [
        'laradock-php-46elks/',
        'docs/',
        'tests/'
        //  'path/to/directory-or-file'
    ],
    'add' => [
        //  ExampleMetric::class => [
        //      ExampleInsight::class,
        //  ]
    ],
    'remove' => [
        //  ExampleInsight::class,
    ],
    'config' => [
        //  ExampleInsight::class => [
        //      'key' => 'value',
        //  ],
    ],
];