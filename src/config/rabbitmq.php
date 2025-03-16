<?php

return [
    'host' => env('RABBITMQ_HOST', 'host.docker.internal'),
    'port' => env('RABBITMQ_PORT', 5672),
    'user' => env('RABBITMQ_USER', 'guest'),
    'password' => env('RABBITMQ_PASSWORD', 'guest'),
    'rmq_timeout' => env('RABBITMQ_TIMEOUT')
];
