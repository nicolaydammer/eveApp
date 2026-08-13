<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\Admin\CharacterController;
use App\Http\Controllers\Web\Admin\MarketController;
use App\Http\Controllers\Web\Admin\ScopeController;
use App\Http\Controllers\Web\Admin\UserController;
use App\Http\Controllers\Web\Configuration\ConfigurationController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\Eve\ListSystemsController;
use App\Http\Controllers\Web\Eve\RegionController;
use App\Http\Controllers\Web\Eve\SystemCostIndexController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\Industry\DirectBuyController;
use App\Http\Controllers\Web\Industry\FullTreeController;
use App\Http\Controllers\Web\Industry\SearchController;
use App\Http\Middleware\IsAdminUser;
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

        Route::get('/', SearchController::class)->name('industry');

        Route::get('/full-tree/{_key}', FullTreeController::class)->name('industry.fullTree');

        Route::get('/direct-buy/{_key}', DirectBuyController::class)->name('industry.directBuy');
    });

Route::prefix('eve')
    ->middleware('auth')
    ->group(function () {
        Route::get('/systems', ListSystemsController::class)->name('eve.listSystems');
        Route::get('/regions', RegionController::class)->name('eve.listRegions');
        Route::get('/indices/{system}', SystemCostIndexController::class)->name('eve.systemCostIndex');
    });

Route::prefix('admin')
    ->middleware(['auth', IsAdminUser::class])
    ->group(function () {
        Route::get('/market', [MarketController::class, 'index'])->name('admin.market.index');

        Route::get('/scopes', [ScopeController::class, 'index'])->name('admin.scopes.index');
        Route::get('/scopes/list', [ScopeController::class, 'listScopes'])->name('admin.scopes.list');

        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::patch('/users/{user}/admin', [UserController::class, 'setAdmin'])->name('admin.users.setAdmin');
        Route::get('/users/list', [UserController::class, 'getUsers'])->name('admin.users.getUsers');

        Route::get('/characters', [CharacterController::class, 'index'])->name('admin.characters.index');

        Route::post('/{type}', [ConfigurationController::class, 'adminStore'])->name('admin.store');
        Route::get('/{type}', [ConfigurationController::class, 'adminGet'])->name('admin.get');
    });
