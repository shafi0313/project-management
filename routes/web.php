<?php

use App\Http\Controllers\SummerNoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::post('/summernote/image/destroy', [SummerNoteController::class, 'imageDestroy'])->name('summernote_image.destroy');
