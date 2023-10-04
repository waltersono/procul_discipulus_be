<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\UniversityController;
use App\Http\Controllers\Dashboard\CourseController;
use App\Http\Controllers\Dashboard\SubjectController;
use App\Http\Controllers\Dashboard\LessionController;
use App\Http\Controllers\Dashboard\OperatorController;
use App\Http\Controllers\Dashboard\StudentController;
use App\Http\Controllers\Dashboard\SchoolController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/** for side bar menu active */
function set_active( $route ) {
    if( is_array( $route ) ){
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/',[DashboardController::class, 'index'])->name('home');

    /*Escolas*/
    Route::resource('universities', UniversityController::class);

    Route::resource('courses', CourseController::class);
    Route::get('courses/student/create',[CourseController::class, 'addStudent'])->name('courses.student.create');
    Route::post('courses/student',[CourseController::class, 'student'])->name('courses.student.store');
    Route::post('courses/student/status',[CourseController::class, 'studentStatus'])->name('subjects.student.status');
    Route::post('courses/requirement',[CourseController::class, 'requirement'])->name('courses.requirement.store');
    Route::post('courses/skill',[CourseController::class, 'skill'])->name('courses.skill.store');
    Route::get('courses/{course_id}/subjects', [CourseController::class, 'subjects'])->name('courses.subjects.fetch');

    Route::resource('subjects', SubjectController::class);
    Route::post('subjects/objectives',[SubjectController::class, 'objective'])->name('subjects.objective.store');
    Route::post('subjects/thematics',[SubjectController::class, 'thematic'])->name('subjects.thematic.store');
    Route::post('subjects/questions',[SubjectController::class, 'question'])->name('subjects.question.store');
    Route::post('subjects/materials',[SubjectController::class, 'material'])->name('subjects.material.store');
    Route::post('subjects/testes',[SubjectController::class, 'testes'])->name('subjects.testes.store');
    Route::post('subjects/testes/questions',[SubjectController::class, 'questionsTestes'])->name('subjects.question.testes.store');
    Route::get('subjects/testes/{teste}/show',[SubjectController::class, 'testesShow'])->name('subjects.testes.show');
    Route::get('subjects/testes/{teste}/edit',[SubjectController::class, 'testesEdit'])->name('subjects.testes.edit');
    Route::put('subjects/testes/{teste}',[SubjectController::class, 'testesUpdate'])->name('subjects.testes.update');

    Route::get('subjects/materials/{material}/edit',[SubjectController::class, 'materialsEdit'])->name('subjects.material.edit');
    Route::put('subjects/materials/{material}',[SubjectController::class, 'materialsUpdate'])->name('subjects.material.update');

    Route::resource('lessions', LessionController::class);
    Route::post('lessions/summary',[LessionController::class, 'summary'])->name('lessions.summary');

    Route::resource('operators', OperatorController::class);
    
    Route::resource('students', StudentController::class);

    Route::resource('schools', SchoolController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('students/{id}/certificates', [DashboardController::class, 'certificates'])->name('students.certificates');
    Route::post('students/certificates/generate', [DashboardController::class, 'generateCertificate'])->name('students.certificates.generate');
});

require __DIR__.'/auth.php';
