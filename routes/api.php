<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Students CRUD
Route::post('/student', [StudentController::class, 'store'])->name('api.students.store'); // C
Route::get('/students', [StudentController::class, 'index'])->name('api.students.index'); // R
Route::put('/students/{id}', [StudentController::class, 'update'])->name('api.students.update'); // U
Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('api.students.destroy'); // D

Route::get('/students/{id}', [StudentController::class, 'show'])->name('api.students.show'); // Get 1 student


// Subjects CRUD
Route::post('/subject', [SubjectController::class, 'store'])->name('api.subjects.store'); // C
Route::get('/subjects', [SubjectController::class, 'index'])->name('api.subjects.index'); // R
Route::put('/subjects/{id}', [SubjectController::class, 'update'])->name('api.subjects.update'); // U
Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('api.subjects.destroy'); // D

Route::get('/subjects/{id}', [SubjectController::class, 'show'])->name('api.subjects.show'); // Get 1 subject