<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('', [StudentController::class, 'getData'])
    ->name('app.student.get-all');

Route::get('/{id}', [StudentController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.student.get-one');

Route::get('/current', [StudentController::class, 'current'])
    ->name('app.student.current');

Route::post('/register', [StudentController::class, 'registerCourse'])
    ->name('app.student.register');
