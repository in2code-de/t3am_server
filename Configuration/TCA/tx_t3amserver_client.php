<?php
return [
    'ctrl' => [
        'label' => 'identifier',
        'descriptionColumn' => 'instance_notice',
        'sortby' => 'tstamp',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'title' => 'T3AM Client',
        'delete' => 'deleted',
        'versioningWS' => false,
        'hideAtCopy' => true,
        'prependAtCopy' => '[copy]',
        'copyAfterDuplFields' => 'instance_notice',
        'searchFields' => 'token,identifier,instance_notice',
        'rootLevel' => 1,
        'iconfile' => 'EXT:t3am_server/ext_icon.svg',
    ],
    'interface' => [
        'always_description' => 0,
        'showRecordFieldList' => 'token,identifier,instance_notice',
    ],
    'types' => [
        '1' => [
            'showitem' => 'token,identifier,instance_notice',
        ],
    ],
    'palettes' => [],
    'columns' => [
        'token' => [
            'label' => 'token (save the form to generate the token)',
            'config' => [
                'type' => 'input',
                'size' => 17,
                'max' => 40,
                'readOnly' => true,
            ],
        ],
        'instance_notice' => [
            'exclude' => true,
            'label' => 'Instance description',
            'config' => [
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            ],
        ],
        'identifier' => [
            'exclude' => true,
            'label' => 'Instance name',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
            ],
        ],
    ],
];
