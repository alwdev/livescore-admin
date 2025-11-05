<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LeagueController;
use App\Http\Controllers\Admin\TeamController;

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
    // Articles specific routes (must be before resource route)
    Route::post('/articles/upload', [ArticleController::class, 'upload'])
        ->name('admin.articles.upload');
    Route::get('/articles/fixtures', [ArticleController::class, 'getFixtures'])
        ->name('admin.articles.fixtures');
    Route::get('/articles/test-fixtures', [ArticleController::class, 'testFixtures'])
        ->name('admin.articles.test-fixtures');
    Route::resource('/articles', ArticleController::class, ['as' => 'admin']);
    Route::resource('/users', UserController::class, ['as' => 'admin']);

    // Leagues management (only index and update)
    Route::get('/leagues', [LeagueController::class, 'index'])->name('admin.leagues.index');
    Route::put('/leagues/{league}', [LeagueController::class, 'update'])->name('admin.leagues.update');

    // Teams management (only index and update)
    Route::get('/teams', [TeamController::class, 'index'])->name('admin.teams.index');
    Route::put('/teams/{team}', [TeamController::class, 'update'])->name('admin.teams.update');
});

require __DIR__ . '/auth.php';
