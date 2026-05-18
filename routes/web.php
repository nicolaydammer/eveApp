<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\IndustryController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/auth/redirectToEveSSO', [AuthController::class, 'redirectToEveSSO'])->name('auth.redirectToEveSSO');
Route::get('/auth/callback', [AuthController::class, 'handleEveCallback'])->name('auth.handleEveCallback');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::post('/set-main-character/{CharacterID}', [DashboardController::class, 'setMainCharacter'])->middleware('auth')->name('dashboard.setMainCharacter');

Route::get('/industry', [IndustryController::class, 'index'])->middleware('auth')->name('industry');

Route::get('/industry/full-tree/{_key}', [\App\Http\Controllers\Web\IndustryController::class, 'getFullTree'])->middleware('auth')->name('industry.fullTree');
Route::get('/industry/direct-buy/{_key}', [\App\Http\Controllers\Web\IndustryController::class, 'instaBuy'])->middleware('auth')->name('industry.instaBuy');
