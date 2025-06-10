<?php

return [

    'paths' => ['api/*', 'products/*'], // 自訂 API 路由

    'allowed_methods' => ['*'], // 允許所有方法 GET POST 等

    'allowed_origins' => [
        'https://shop.qiushawa.studio', // 前端來源
        'https://admin.qiushawa.studio', // 管理後台來源
        'https://api.qiushawa.studio', // API 來源
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
