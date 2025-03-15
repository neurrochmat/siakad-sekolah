<?php

use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\EducationLevelController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\DBBackupController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\SchoolController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});

Route::permanentRedirect('/', '/login');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('profil', ProfilController::class)->except('destroy');

    // Manajemen User
    Route::middleware(['permission:manage-users'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('users-trashed', [UserController::class, 'trashed'])->name('users.trashed');
        Route::patch('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('menus', MenuController::class);
        Route::resource('permissions', PermissionController::class)->only('store', 'destroy');
    });

    // Data Master
    Route::middleware(['permission:manage-education-levels'])->group(function () {
        Route::resource('education-levels', EducationLevelController::class);
    });

    Route::middleware(['permission:manage-academic-years'])->group(function () {
        Route::resource('academic-years', AcademicYearController::class);
    });

    Route::middleware(['permission:manage-classes'])->group(function () {
        Route::resource('classes', ClassController::class);
    });

    Route::middleware(['permission:manage-subjects'])->group(function () {
        Route::resource('subjects', SubjectController::class);
    });

    Route::middleware(['permission:manage-teachers'])->group(function () {
        Route::resource('teachers', TeacherController::class);
    });

    Route::middleware(['permission:manage-students'])->group(function () {
        Route::resource('students', StudentController::class);
    });

    // Presensi
    Route::middleware(['permission:manage-attendances'])->group(function () {
        Route::resource('attendances', AttendanceController::class);
        Route::get('attendances/get-students/{class}', [AttendanceController::class, 'getStudents'])
            ->name('attendances.get-students');
    });

    // Manajemen Sekolah
    Route::middleware(['permission:manage-schools'])->group(function () {
        Route::resource('schools', SchoolController::class);
    });

    // Backup Database (Admin Only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('dbbackup', [DBBackupController::class, 'DBDataBackup'])->name('dbbackup');
    });
});

// Development routes - remove in production
if (config('app.env') === 'local') {
    Route::get('/debug-menu', function() {
        return response()->json(
            \App\Models\Menu::with(['submenus' => function($q) {
                $q->orderBy('order');
                $q->with(['submenus' => function($q) {
                    $q->orderBy('order');
                }]);
            }])->whereNull('parent_id')->orderBy('order')->get()
        );
    });

    Route::get('/check-permission', function() {
        $user = Auth::user();
        return response()->json([
            'user_id' => $user->id,
            'name' => $user->name,
            'roles' => $user->roles->pluck('name'),
            'permissions' => $user->getAllPermissions()->pluck('name')
        ]);
    })->middleware('auth');
}
