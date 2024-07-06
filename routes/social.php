<?php

use Illuminate\Support\Facades\Route;
use Rzb\SocialAuth\Http\Controllers\SocialAuthController;

Route::get('auth/social/{provider}/{sociable}', [SocialAuthController::class, 'redirect'])
    ->where('provider', 'google')
    ->name('social.redirect');
Route::post('auth/social/{provider}/{sociable}', [SocialAuthController::class, 'callback'])
    ->where('provider', 'google')
    ->name('social.callback');
