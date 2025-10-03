<?php

use App\Documentation\Correction\Controllers\CorrectionsController;
use Illuminate\Support\Facades\Route;


Route::post('/{lang}/{product}/{version}/{page}/corrections', [CorrectionsController::class, 'store'])
    ->where([
        'page' => '.*',
    ])
    ->name('corrections.store');

