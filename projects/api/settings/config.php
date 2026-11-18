<?php
/**
 * api/settings/config.php
 * Read in every controller, model and view as self::$cfg->web_title and so on.
 */
return [
    'web_title' => 'REST API',
    // Templates are read from themes/{skin}/ (the page index.php)
    'skin' => 'default',
    'timezone' => 'Asia/Bangkok',
    // Kotchasan\ApiController answers 503 "Unavailable API" until both are set.
    // Tokens that may write (POST, PUT, DELETE): name => token. Use long random values on a real site.
    'api_tokens' => [
        'demo' => 'kotchasan-demo-token'
    ],
    // IP addresses that may call the API. 0.0.0.0 allows every address.
    'api_ips' => ['0.0.0.0']
];
