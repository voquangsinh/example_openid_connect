<?php
return [
    'google' => [
        'client_id' => env('GG_CLIENT_ID', ''),
        'secret' => env('GG_CLIENT_SECRET', ''),
        'callback' => env('GG_REDIRECT_URI', ''),
    ],
    'facebook' => [],
    'github' => [],
];
