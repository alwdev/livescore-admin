<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\UserController;

// เส้นทางของผู้ใช้ทั่วไป (เช่น profile)
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🔒 ส่วนของ Admin Panel
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/settings', [SettingController::class, 'edit'])->name('admin.settings.edit');
    Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
    Route::resource('/articles', ArticleController::class, ['as' => 'admin']);
    Route::post('/admin/articles/upload', [ArticleController::class, 'upload'])
        ->name('admin.articles.upload');
    Route::resource('/users', UserController::class, ['as' => 'admin']);
});

require __DIR__ . '/auth.php';
