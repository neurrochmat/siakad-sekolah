<?php

use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\EducationLevelController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes untuk autentikasi
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');

// Routes untuk admin super
Route::middleware(['auth:sanctum', 'role:admin_super'])->group(function () {
    Route::apiResource('schools', SchoolController::class);
});

// Routes untuk admin sekolah
Route::middleware(['auth:sanctum', 'role:admin_sekolah', 'school.access'])->group(function () {
    Route::apiResource('education-levels', EducationLevelController::class);
    Route::apiResource('academic-years', AcademicYearController::class);
    Route::apiResource('classes', ClassController::class);
    Route::apiResource('subjects', SubjectController::class);
    Route::apiResource('teachers', TeacherController::class);
    Route::apiResource('students', StudentController::class);
    Route::apiResource('attendances', AttendanceController::class);

    // Route khusus untuk presensi
    Route::post('attendances/bulk', [AttendanceController::class, 'bulkStore']);
    Route::get('attendances/report', [AttendanceController::class, 'report']);

    // Route untuk mengambil daftar kelas berdasarkan sekolah
    Route::get('schools/{school}/classes', [ClassController::class, 'getBySchool']);
});

// Routes untuk guru
Route::middleware(['auth:sanctum', 'role:guru', 'school.access'])->group(function () {
    Route::get('attendances', [AttendanceController::class, 'index']);
    Route::post('attendances/bulk', [AttendanceController::class, 'bulkStore']);
    Route::get('attendances/report', [AttendanceController::class, 'report']);
});

// Routes untuk siswa
Route::middleware(['auth:sanctum', 'role:siswa', 'school.access'])->group(function () {
    Route::get('attendances', [AttendanceController::class, 'index']);
});
