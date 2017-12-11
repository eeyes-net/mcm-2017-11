<?php

return [
    'account' => [
        'url' => env('EEYES_ACCOUNT_URL', 'https://account.eeyes.net'),
        'app' => [
            'id' => env('EEYES_ACCOUNT_APP_ID'),
            'secret' => env('EEYES_ACCOUNT_APP_SECRET'),
        ],
        'admin_app' => [
            'id' => env('EEYES_ACCOUNT_ADMIN_APP_ID'),
            'secret' => env('EEYES_ACCOUNT_ADMIN_APP_SECRET'),
        ],
    ],
];
