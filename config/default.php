<?php
return [
    'http' => [
        'host' => '0.0.0.0',
        'port' => 9501,
        //'rpcEnable' => 1,
        'setting' => [
            'worker_num' => 2
        ]
    ],
    'rpc' => [
        'host' => '0.0.0.0',
        'port' => 9200,
        'setting' => [
            'worker_num' => 2
        ]
    ]
];
