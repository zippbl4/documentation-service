<?php

use App\Auth\Controllers\AuthController;
use App\Documentation\Searcher\Controllers\DocumentationSearcherController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(static function (): void {
    Route::get('login', [AuthController::class, 'loginForm'])->name('loginForm');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/search', [DocumentationSearcherController::class, 'search'])
    ->name('pages.search');

