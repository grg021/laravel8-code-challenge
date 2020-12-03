<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CourseEnrollmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizAnswerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

\Illuminate\Support\Facades\Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [HomeController::class, 'index'])
    ->name('home')
    ->middleware('auth');

Route::get('/courses/{slug}', [CourseEnrollmentController::class, 'show'])
    ->name('courseEnrollments.show')
    ->middleware('auth');

Route::post('/course-enrollments/{slug}', [CourseEnrollmentController::class, 'store'])
    ->name('courseEnrollments.store')
    ->middleware('auth');

Route::get('/courses/{slug}/{number}', [LessonController::class, 'show'])
    ->name('lessons.show')
    ->middleware('auth');

Route::post('/quiz-answers/{id}', [QuizAnswerController::class, 'store'])
    ->name('quizAnswers.store')
    ->middleware('auth');

Route::get('/random-login', [LoginController::class, 'loginAsRandomUser'])
    ->name('auth.randomLogin');
