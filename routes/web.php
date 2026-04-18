<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaItemController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('site.home');
Route::get('/pagina/{slug}', [SiteController::class, 'page'])->name('site.page');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->middleware('guest')->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login.store');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::resource('pages', PageController::class);
        Route::resource('sections', SectionController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('media', MediaItemController::class);
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
