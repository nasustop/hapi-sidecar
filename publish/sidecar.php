<?php

declare(strict_types=1);
/**
 * This file is part of Hapi.
 *
 * @link     https://www.nasus.top
 * @document https://wiki.nasus.top
 * @contact  xupengfei@xupengfei.net
 * @license  https://github.com/nasustop/hapi-sidecar/blob/master/LICENSE
 */
return [
    'demo' => [
        'enable' => true,
        'build' => true,
        'build_work_dir' => BASE_PATH . '/vendor/nasustop/hapi-sidecar/example/demo',
        'build_command' => 'cmd/server.go',
        'executable' => \Nasustop\HapiSidecar\ConfigProvider::sidecar_address('demo', false),
        'socket_address' => \Nasustop\HapiSidecar\ConfigProvider::sidecar_address('demo') . '.sock',
        'pool' => [
            'min_connections' => swoole_cpu_num(),
            'max_connections' => swoole_cpu_num() * 2,
            'connect_timeout' => 10.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => 60,
        ],
    ],
    'mongo' => [
        'enable' => true,
        'build' => true,
        'build_work_dir' => BASE_PATH . '/vendor/nasustop/hapi-sidecar/example/mongo',
        'build_command' => 'cmd/server.go',
        'executable' => \Nasustop\HapiSidecar\ConfigProvider::sidecar_address('mongo', false),
        'socket_address' => \Nasustop\HapiSidecar\ConfigProvider::sidecar_address('mongo') . '.sock',
        'host' => env('MONGO_HOST', 'localhost'),
        'port' => (int) env('MONGO_PORT', 27017),
        'username' => env('MONGO_USERNAME', 'hapi'),
        'password' => env('MONGO_PASSWORD', '123456'),
        'database' => env('MONGO_DATABASE', 'hapi'),
        'pool' => [
            'min_connections' => swoole_cpu_num(),
            'max_connections' => swoole_cpu_num() * 2,
            'connect_timeout' => 10.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => 60,
        ],
    ],
];
