<?php
$EM_CONF['t3am_server'] = [
    'title' => 'T3AM Server',
    'description' => 'T3AM Server - TYPO3 Authentication Manager Server',
    'category' => 'services',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'author' => 'Oliver Eglseder',
    'author_email' => 'php@vxvr.de',
    'author_company' => 'in2code GmbH',
    'version' => '4.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
