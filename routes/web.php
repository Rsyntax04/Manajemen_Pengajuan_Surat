<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\MoController;
use App\Http\Controllers\UserController;

Route::get('/', [AuthController::class, 'login'])->name('login');

Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');

Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'processForgotPassword'])->name('password.email');

Route::get('/verify-code/{id}', [AuthController::class, 'verifyCode'])->name('password.verify');

Route::post('/verify-code/{id}', [AuthController::class, 'checkCode'])->name('password.check');

Route::get('/reset-password/{id}', [AuthController::class, 'resetPassword'])->name('password.reset');

Route::post('/reset-password/{id}', [AuthController::class, 'updatePassword'])->name('password.update');

Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');
Route::middleware('auth')->group(function () {
    // Admin Dashboard
    Route::prefix('admin')
        ->middleware('role:admin')
        ->group(function () {
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

            Route::get('/users', [AdminController::class, 'ShowUsers'])->name('users.index');

            Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');

            Route::get('/users/create', [AdminController::class, 'CreateUser'])->name('users.create');

            Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('users.edit');

            Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');

            Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.destroy');

            Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity_logs');

            Route::get('/jenis-surat', [AdminController::class, 'showjenisSurat'])->name('jenis-surat.index');

            Route::get('/jenis-surat/create', [AdminController::class, 'createJenisSurat'])->name('jenis-surat.create');

            Route::post('/jenis-surat/store', [AdminController::class, 'storeJenisSurat'])->name('jenis-surat.store');

            Route::get('/jenis-surat/{id}/edit', [AdminController::class, 'editJenisSurat'])->name('jenis-surat.edit');

            Route::put('/jenis-surat/{id}', [AdminController::class, 'updateJenisSurat'])->name('jenis-surat.update');

            Route::delete('/jenis-surat/{id}', [AdminController::class, 'deleteJenisSurat'])->name('jenis-surat.delete');
        });

    // MO Dashboard
    Route::prefix('mo')
        ->middleware(['auth', 'role:mo'])
        ->group(function () {
            Route::get('/dashboard', [MoController::class, 'index'])->name('mo.dashboard');

            Route::get('/approval', [MoController::class, 'approvalPage'])->name('mo.approval');

            Route::post('/approve/{id}', [MoController::class, 'approve'])->name('mo.approve');

            Route::post('/reject/{id}', [MoController::class, 'reject'])->name('mo.reject');
            Route::post('/revisi/{id}', [MoController::class, 'revisi'])->name('mo.revisi');
            Route::get('/pengajuan/{id}', [MoController::class, 'show'])->name('mo.pengajuan.show');

            Route::get('/template-builder', [MoController::class, 'templateBuilder'])->name('mo.template.builder');
            Route::post('/template-builder', [MoController::class, 'uploadWord'])->name('mo.template.upload');
            Route::get('/history', [MoController::class, 'history'])->name('mo.history');
        });

    // User Dashboard
    Route::prefix('user')
        ->middleware(['role:mahasiswa,dosen'])
        ->group(function () {
            Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
            Route::get('/pengajuan', [UserController::class, 'pengajuan'])->name('user.pengajuan');
            Route::post('/pengajuan/store', [UserController::class, 'storePengajuan'])->name('user.pengajuan.store');
            Route::get('/pengajuan/{id}/edit', [UserController::class, 'editPengajuan'])->name('user.pengajuan.edit');
            Route::put('/pengajuan/{id}', [UserController::class, 'updatePengajuan'])->name('user.pengajuan.update');
            Route::get('/history', [UserController::class, 'history'])->name('user.history');
            Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
            Route::get('/user/profile/edit', [UserController::class, 'editProfile'])->name('user.profile.edit');
            Route::put('/user/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
            Route::get('/user/pengajuan/{id}/view', [UserController::class, 'viewPdf'])->name('user.pengajuan.view');
            Route::get('/user/pengajuan/{id}/download', [UserController::class, 'downloadSurat'])->name('user.pengajuan.download');
        });
});
