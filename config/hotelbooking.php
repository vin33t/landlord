<?php

return [
    'stuba' => [
        'username' => env('STUBA_USERNAME'),
        'password' => env('STUBA_PASSWORD'),
        'endpoint' => env('STUBA_ENDPOINT', 'https://api.stuba.com'),
    ],
    'ratehawk' => [
        'key_id' => env('RATEHAWK_KEY_ID'),
        'key_type' => env('RATEHAWK_KEY_TYPE'),
        'api_key' => env('RATEHAWK_API_KEY'),
        'endpoint' => env('RATEHAWK_ENDPOINT', 'https://api.ratehawk.com'),
    ],
    'travellanda' => [
        'username' => env('TRAVELLANDA_USERNAME'),
        'password' => env('TRAVELLANDA_PASSWORD'),
        'endpoint' => env('TRAVELLANDA_ENDPOINT', 'https://api.travellanda.com'),
    ],
];
