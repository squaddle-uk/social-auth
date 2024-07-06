<?php

use App\Models\User;

return [
    'defaults' => [
        'provider' => 'google',
        'sociable' => 'user',
    ],

    'sociables' => [
        'user' => [
            'model' => User::class,
            'providers' => [
                'google',
            ],
        ],
    ],
];
