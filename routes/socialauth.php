<?php

use Illuminate\Support\Facades\Route;

Route::prefix('{provider}/{sociable}')
    ->name('social.')
    ->group(function () {
        Route::get('/', 'redirect')->name('redirect');
        Route::post('/', 'callback')->name('callback');
    }
);
