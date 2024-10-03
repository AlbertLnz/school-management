<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Classroom
Route::get('/school/classrooms', [ClassroomController::class, 'index'])->name('api.classrooms.index');

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


// Grades CRUD
// ...


// Obtenir notes d’assignatures per estudiant. + Obtenir nota mitjana de les notes d’una assignatura
Route::get('/students/{studentId}/{subjectId}/grades', [GradeController::class, 'studentSubjectGrades'])->name('api.students.studentSubjectGrades');

// Obtenir mitjana de les notes de les assignatures per estudiant
Route::get('/students/{studentId}/grades/average', [GradeController::class, 'averageStudentGrades'])->name('api.students.averageStudentGrades');

// Obtenir mitjana de totes les notes de tots els estudiants (TOTAL DE LA ESCOLA).
Route::get('/school/grades/average', [GradeController::class, 'averageSchoolGrades'])->name('api.students.averageSchoolGrades');
// Obtenir mitjana de totes les notes de tots els estudiants (TOTAL DE LA CLASSE).
Route::get('/school/{courseId}/grades/average', [GradeController::class, 'averageSchoolCourseGrades'])->name('api.students.averageSchoolCourseGrades');
