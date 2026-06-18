<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\Industry\DirectBuyController;
use App\Http\Controllers\Web\Industry\FullTreeController;
use App\Http\Controllers\Web\Industry\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/auth/redirectToEveSSO', [AuthController::class, 'redirectToEveSSO'])->name('auth.redirectToEveSSO');
Route::get('/auth/callback', [AuthController::class, 'handleEveCallback'])->name('auth.handleEveCallback');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::post('/set-main-character/{CharacterID}', [DashboardController::class, 'setMainCharacter'])->middleware('auth')->name('dashboard.setMainCharacter');

Route::prefix('industry')
    ->middleware('auth')
    ->group(function () {

        Route::get('/', SearchController::class)
            ->name('industry');

        Route::get('/full-tree/{_key}', FullTreeController::class)
            ->name('industry.fullTree');

        Route::get('/direct-buy/{_key}', DirectBuyController::class)
            ->name('industry.directBuy');
    });
