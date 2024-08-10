<?php

use App\Models\User;
use Rzb\SocialAuth\Http\Controllers\SocialAuthController;

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
                'facebook',
                'twitter',
            ],
        ],
    ],

    'routes' => [
        'controller' => SocialAuthController::class,
        'middleware' => null,
        'prefix' => 'auth/social',
    ],
];
