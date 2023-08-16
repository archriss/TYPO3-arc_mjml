<?php

return [
    'frontend' => [
        'archriss/arc-mjml/mjml-router' => [
            'target' => \Archriss\ArcMjml\Middleware\MjmlRouter::class,
            'after' => [
                'typo3/cms-frontend/tsfe'
            ],
            'before' => [
                'typo3/cms-frontend/shortcut-and-mountpoint-redirect'
            ],
        ],
        'archriss/arc-mjml/mjml-parser' => [
            'target' => \Archriss\ArcMjml\Middleware\MjmlParser::class,
            'after' => [
                'archriss/arc-mjml/mjml-router'
            ],
            'before' => [
                'typo3/cms-frontend/shortcut-and-mountpoint-redirect'
            ],
        ],
    ]
];