<?php

use ConfrariaWeb\SocialiteAutoPilot\Controllers\SocialiteAutoPilotController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->group(function () {

        Route::name('dashboard.')
            ->prefix('dashboard')
            ->group(function () {

                Route::get('/auth/{provider}/redirect', [SocialiteAutoPilotController::class, 'redirect'])->name('auth.redirect');
                Route::get('/auth/{provider}/callback', [SocialiteAutoPilotController::class, 'callback'])->name('auth.callback');

                Route::get('/accounts', [SocialiteAutoPilotController::class, 'accounts'])->name('accounts.index');
                Route::get('/medias', [SocialiteAutoPilotController::class, 'medias'])->name('medias.index');
                Route::get('/medias/create', [SocialiteAutoPilotController::class, 'createMedias'])->name('medias.create');

            });
    });
