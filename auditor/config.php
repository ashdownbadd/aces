<?php

declare(strict_types=1);

return [

    'ignore' => [

        '.git',
        '.github',
        'vendor',
        'node_modules',
        'uploads',

    ],

    'scan' => [

        'controllers',
        'helpers',
        'views',
        'routes',
        'config',
        'assets/css',
        'assets/js',

    ],

];
