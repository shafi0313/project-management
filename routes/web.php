<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SummerNoteController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::post('/summernote/image/destroy', [SummerNoteController::class, 'imageDestroy'])->name('summernote_image.destroy');
