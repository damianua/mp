<?php

return [
    'base_uri' => env('ESYNC_BASE_URI', 'https://intertop.ua'),
    'auth' => [
        'user' => env('ESYNC_USER'),
        'password' => env('ESYNC_PASS')
    ],
    'resources' => [
        'handbooks' => [
            'xxx-brands' => '/api/v1/iblock/3',
            'xxx-year-seasons' => '/api/v1/iblock/4',
            'xxx-sole_material' => '/api/v1/iblock/5',
            'xxx-good_type' => '/api/v1/iblock/6',
            'xxx-color' => '/api/v1/iblock/7',
            'xxx-pol' => '/api/v1/iblock/10',
            //'xxx-technologies' => '/api/v1/iblock/38'
        ],
        'product_properties' => '/api/v1/iblock/2/property'
    ],
];