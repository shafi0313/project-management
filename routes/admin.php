<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectFileController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SubSectionController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\Setting\AppDbBackupController;
use App\Http\Controllers\Setting\Permission\PermissionController;
use App\Http\Controllers\Setting\Permission\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/project', [DashboardController::class, 'project'])->name('dashboard.projects');

// Role & Permission
Route::post('/role/permission/{role}', [RoleController::class, 'assignPermission'])->name('role.permission');
Route::resource('/role', RoleController::class);
Route::resource('/permission', PermissionController::class);

// App DB Backup
Route::controller(AppDbBackupController::class)->prefix('app-db-backup')->group(function () {
    Route::get('/password', 'password')->name('backup.password');
    Route::post('/checkPassword', 'checkPassword')->name('backup.checkPassword');
    Route::get('/confirm', 'index')->name('backup.index');
    Route::post('/backup-file', 'backupFiles')->name('backup.files');
    Route::post('/backup-db', 'backupDb')->name('backup.db');
    Route::post('/backup-download/{name}/{ext}', 'downloadBackup')->name('backup.download');
    Route::post('/backup-delete/{name}/{ext}', 'deleteBackup')->name('backup.delete');
});

// Global Ajax Route
Route::get('select-2-ajax', [AjaxController::class, 'select2'])->name('select2');
Route::post('response', [AjaxController::class, 'response'])->name('ajax');

Route::resource('/admin-users', AdminUserController::class)->except(['show', 'create']);
Route::patch('/admin-users/is-active/{user}', [AdminUserController::class, 'status'])->name('admin_users.is_active');

Route::resource('/my-profiles', MyProfileController::class)->only(['index', 'edit']);

Route::resource('/sections', SectionController::class)->except(['show', 'create']);
Route::patch('/sections/is-active/{section}', [SectionController::class, 'status'])->name('sections.is_active');

Route::resource('/sub-sections', SubSectionController::class)->except(['show', 'create']);
Route::patch('/sub-sections/is-active/{subSection}', [SubSectionController::class, 'status'])->name('sub_sections.is_active');

Route::resource('/designations', DesignationController::class)->except(['show', 'create']);
Route::patch('/designations/is-active/{designation}', [DesignationController::class, 'status'])->name('designations.is_active');

Route::resource('/projects', ProjectController::class)->except(['create']);
Route::get('/project/tasks/{projectId}', [ProjectController::class, 'task'])->name('projects.tasks');
Route::patch('/projects/is-active/{project}', [ProjectController::class, 'status'])->name('projects.is_active');
// Route::resource('/project-files', ProjectFileController::class)->except(['index', 'create']);

Route::resource('/tasks', TaskController::class)->except(['create']);
// Route::resource('/dropzone', DropZoneController::class)->except(['index', 'create']);

Route::controller(ProjectFileController::class)->prefix('project-files')->name('project_files.')->group(function () {
    Route::get('/index', 'index')->name('index');
    Route::post('/store', 'store')->name('store');
    Route::delete('/destroy', 'destroy')->name('destroy');
    Route::delete('/delete/{id}', 'delete')->name('delete');
});
