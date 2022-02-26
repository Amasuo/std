<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CourseController;

Route::get('', [CourseController::class, 'getData'])
    ->name('app.course.get-all');

Route::get('/{id}', [CourseController::class, 'getItem'])
    ->where('id', '[0-9]+')
    ->name('app.course.get-one');

Route::post('', [CourseController::class, 'store'])
    ->name('app.course.create');

Route::match(['put', 'patch'],'/{id}', [CourseController::class, 'update'])
    ->where('id', '[0-9]+')
    ->name('app.course.update');

Route::delete('/{id}', [CourseController::class, 'delete'])
    ->where('id', '[0-9]+')
    ->name('app.course.delete');
