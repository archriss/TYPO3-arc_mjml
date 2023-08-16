<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Arc MJML',
    'description' => 'A MJML parser extension. No plugin, just middlewares routing and parsing newsletter doktype to a mjml content',
    'category' => 'misc',
    'author' => '',
    'author_email' => '',
    'author_company' => 'Archriss',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '11.5.1',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
