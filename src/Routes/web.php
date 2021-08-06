<?php

use ConfrariaWeb\SocialiteAutoPilot\Controllers\SocialiteAutoPilotController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->group(function () {

        Route::name('dashboard.')
            ->prefix('dashboard')
            ->group(function () {

                Route::get('/auth/{provider}/redirect', [SocialiteAutoPilotController::class, 'redirect']);
                Route::get('/auth/{provider}/callback', [SocialiteAutoPilotController::class, 'callback']);

                /*Route::name('youtube.')
                    ->prefix('youtube')
                    ->group(function () {
                        Route::resource('channels', YoutubeChannelController::class);
                    });

                Route::resource('youtube', YoutubeController::class);*/
            });
    });
