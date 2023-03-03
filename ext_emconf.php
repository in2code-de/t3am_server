<?php
$EM_CONF['t3am_server'] = [
    'title' => 'T3AM Server',
    'description' => 'T3AM Server - TYPO3 Authentication Manager Server',
    'category' => 'services',
    'state' => 'obsolete',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'author' => 'Oliver Eglseder',
    'author_email' => 'php@vxvr.de',
    'author_company' => 'in2code GmbH',
    'version' => '3.0.2',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-10.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
