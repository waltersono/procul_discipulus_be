<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UniversityController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\LessionController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [AuthController::class, 'reset']);
Route::get('/user-profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
Route::get('/tokenVerify', [AuthController::class, 'tokenVerify'])->middleware('auth:sanctum');

Route::resource('universities', UniversityController::class);
Route::resource('courses', CourseController::class);
Route::post('courses/{course}/requirements',[CourseController::class, 'requirements']);
Route::post('courses/{course}/skills',[CourseController::class, 'skills']);
Route::get('courses/{course}/subjects',[CourseController::class, 'showSubjects']);
Route::post('courses/student',[CourseController::class, 'student']);

Route::resource('subjects', SubjectController::class);
Route::post('subjects/{subject}/objectives',[SubjectController::class, 'objectives']);
Route::post('subjects/{subject}/thematics',[SubjectController::class, 'thematics']);
Route::post('subjects/thematics/{subject}/lessions',[SubjectController::class, 'lessions']);
Route::get('subjects/thematics/{subject}/lessions',[SubjectController::class, 'showLessions']);
Route::get('subjects/{subject}/material',[SubjectController::class, 'showMaterial']);

 
Route::resource('lessions', LessionController::class)->middleware('auth:sanctum');
Route::get('students/courses',[StudentController::class, 'courses'])->middleware('auth:sanctum');
Route::get('students/subjects/{subject}/lessions',[StudentController::class, 'lessions'])->middleware('auth:sanctum');
Route::get('students/lessions/{lession}',[StudentController::class, 'lession'])->middleware('auth:sanctum');
Route::post('students/lessions/start',[StudentController::class, 'startLession'])->middleware('auth:sanctum');
Route::post('students/lessions/end',[StudentController::class, 'endLession'])->middleware('auth:sanctum');
Route::post('/students/quiz/answers', [StudentController::class, 'quizAnswers'])->middleware('auth:sanctum');
Route::post('/students/quiz/marks', [StudentController::class, 'getQuizMarks'])->middleware('auth:sanctum');
Route::get('/students/subjects/{id}/tests', [StudentController::class, 'getTests'])->middleware('auth:sanctum');
Route::post('students/tests/start',[StudentController::class, 'startTest'])->middleware('auth:sanctum');
Route::get('students/teste/{id}',[StudentController::class, 'teste'])->middleware('auth:sanctum');
Route::post('/students/test/answers', [StudentController::class, 'testAnswers'])->middleware('auth:sanctum');
Route::get('/students/test/{id}/marks', [StudentController::class, 'getTestMarks'])->middleware('auth:sanctum');
Route::get('/students/{type}/certificates', [StudentController::class, 'certificates'])->middleware('auth:sanctum');
Route::post('/students/certificates/generate', [StudentController::class, 'generateCertificate'])->middleware('auth:sanctum');