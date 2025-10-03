<?php

use App\Documentation\Diff\Controllers\CompareController;
use App\Documentation\Diff\Controllers\DiffController;
use Illuminate\Support\Facades\Route;

Route::get('/diff/;{page0};{page1}', [DiffController::class, 'diff'])
    ->where(['page0' => '.*', 'page1' => '.*'])
    ->name('pages.diff');

Route::get('/compare/;{pageN}', [CompareController::class, 'compare'])
    ->where(['pageN' => '.*'])
    ->name('pages.compare');
