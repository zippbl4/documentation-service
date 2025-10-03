<?php

use App\Documentation\Viewer\Controllers\DocumentationViewerController;
use App\Documentation\Viewer\Controllers\WikiController;
use Illuminate\Support\Facades\Route;


Route::get('/{lang}/wiki/', [WikiController::class, 'index'])
    ->middleware('web')
    ->name('wiki.index');

Route::get('/{lang}/wiki/create', [WikiController::class, 'create'])
    ->middleware('web')
    ->name('wiki.create.root.page');

Route::get('/{lang}/wiki/{page}/create', [WikiController::class, 'create'])
    ->middleware('web')
    ->where(['page' => '.*'])
    ->name('wiki.create.page');

Route::post('/{lang}/wiki/store', [WikiController::class, 'store'])
    ->middleware('web')
    ->name('wiki.store.page');

Route::get('/{lang}/wiki/{page}/edit', [WikiController::class, 'edit'])
    ->middleware('web')
    ->where(['page' => '.*'])
    ->name('wiki.edit.page');

Route::post('/{lang}/wiki/update', [WikiController::class, 'update'])
    ->middleware('web')
    ->name('wiki.update.page');

Route::get('/{lang}/wiki/{page}', [WikiController::class, 'show'])
    ->middleware('web')
    ->where(['page' => '.*'])
    ->name('wiki.show.page');

Route::get('/{lang}/docs/{product}/{version}/{page?}', [DocumentationViewerController::class, 'show'])
    ->middleware('auth.product')
    ->where([
        'page' => '^(?!' . str_replace('/', '', config('nova.path')) . '|nova-api|nova-vendor).*$',
    ])
    ->name('docs.show.page');

