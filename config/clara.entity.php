<?php

/**
 * Default config values
 */
return [
    
    'route' => [
        'prefix'    => 'admin',
        'middleware' => ['web', \CeddyG\ClaraSentinel\Http\Middleware\SentinelAccessMiddleware::class]
    ]
    
];
