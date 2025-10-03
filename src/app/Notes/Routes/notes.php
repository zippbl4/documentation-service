<?php

use App\Notes\Controllers\NotesController;
use Illuminate\Support\Facades\Route;

Route::middleware([])->group(function () {
    Route::get('/notes', [NotesController::class, 'index'])->name('notes.index');
    Route::post('/notes', [NotesController::class, 'store'])->name('notes.store');
    Route::put('/notes/{note}', [NotesController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NotesController::class, 'destroy'])->name('notes.destroy');
});
