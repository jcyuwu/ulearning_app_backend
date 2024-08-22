<?php

use App\Http\Controllers\Api\LessonController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return 'Laravel is working!';
});

Route::get('/video/files/{filename}', [LessonController::class, 'stream'])->name('video.stream');