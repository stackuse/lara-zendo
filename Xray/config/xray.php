<?php

return [
    'action' => [
        'show' => env('XRAY_SHOW', false),
        'trace' => env('XRAY_TRACE', false),
    ],
    'probes' => [
        'host' => ['enable' => true],  // Php version
        'app' => ['enable' => true], // Laravel version and environment
        'request' => ['enable' => true],  // Only one can be enabled..
        'model' => [
            'enable' => true,
            'explain' => false,
        ], // Show database (PDO) queries and bindings
        'usage' => ['enable' => true], // Laravel version and environment
        'cache' => ['enable' => true], // Laravel version and environment
        'event' => ['enable' => true],  // Only one can be enabled..
    ],

    'trace' => [
        'sample' => [ // 有优先级
            'time' => env('XRAY_SAMPLE_TIME', 5), // 执行时间 5，单位秒
            'sql' => env('XRAY_SAMPLE_SQL_COUNT', 10), // 执行sql的数据
            'memory' => env('XRAY_SAMPLE_MEMORY', 100), // 内存占用，单位M
            'rate' => env('XRAY_SAMPLE_RATE', 100), // 采样率 1 / rate
        ],

        'driver' => env('XRAY_TRACE_DRIVER', 'file'), // redis, file, http
        'file' => [
            'path' => storage_path(env('XRAY_FILE_PATH', 'logs')), // For file driver
        ],
        'redis' => [
            'connection' => env('XRAY_REDIS_CONNECTION', 'default'),
            'channel' => env('XRAY_REDIS_CHANNEL', 'xray-channel'),
        ],
        'http' => [
            'url' => env('XRAY_HTTP_URL'),
            'key' => env('AUTH_INNER_KEY'),
        ],
    ],
];
