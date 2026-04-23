<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('site.home');
Route::get('/pagina/{slug}', [SiteController::class, 'page'])->name('site.page');

Route::redirect('/login', '/admin/login')->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->middleware('guest')->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login.store');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::redirect('/', '/admin/home');
        Route::get('/home', [HomeController::class, 'edit'])->name('home.edit');
        Route::put('/home', [HomeController::class, 'update'])->name('home.update');
        Route::post('/home/carousel', [HomeController::class, 'carouselStore'])->name('home.carousel.store');
        Route::put('/home/carousel/{item}', [HomeController::class, 'carouselUpdate'])->name('home.carousel.update');
        Route::delete('/home/carousel/{item}', [HomeController::class, 'carouselDestroy'])->name('home.carousel.destroy');
        Route::post('/home/carousel/reorder', [HomeController::class, 'carouselReorder'])->name('home.carousel.reorder');
        Route::post('/home/cards', [HomeController::class, 'cardStore'])->name('home.cards.store');
        Route::put('/home/cards/{card}', [HomeController::class, 'cardUpdate'])->name('home.cards.update');
        Route::delete('/home/cards/{card}', [HomeController::class, 'cardDestroy'])->name('home.cards.destroy');
        Route::post('/home/cards/reorder', [HomeController::class, 'cardReorder'])->name('home.cards.reorder');
    });
});
