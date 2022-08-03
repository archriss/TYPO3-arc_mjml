<?php

return [
    'frontend' => [
        'archriss/arc-mjml/mjml-router' => [
            'target' => \Archriss\ArcMjml\Middleware\MjmlRouter::class,
            'before' => [
                'typo3/cms-frontend/authentication'
            ],
        ],
        'archriss/arc-mjml/mjml-parser' => [
            'target' => \Archriss\ArcMjml\Middleware\MjmlParser::class,
            'after' => [
                'archriss/arc-mjml/mjml-router'
            ],
            'before' => [
                'typo3/cms-frontend/authentication'
            ],
        ],
    ]
];