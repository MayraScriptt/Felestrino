<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AboutCompanyController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('site.home');
Route::get('/projetos', [SiteController::class, 'projects'])->name('site.projects');
Route::get('/projetos/{project:slug}', [SiteController::class, 'project'])->name('site.projects.show');
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
        Route::post('/home/cards/{card}/detail-image', [HomeController::class, 'cardDetailImageStore'])->name('home.cards.detail-image.store');
        Route::delete('/home/cards/{card}', [HomeController::class, 'cardDestroy'])->name('home.cards.destroy');
        Route::post('/home/cards/reorder', [HomeController::class, 'cardReorder'])->name('home.cards.reorder');

        Route::get('/sobre-a-empresa', [AboutCompanyController::class, 'edit'])->name('about-company.edit');
        Route::put('/sobre-a-empresa', [AboutCompanyController::class, 'update'])->name('about-company.update');
        Route::put('/sobre-a-empresa/media-layout', [AboutCompanyController::class, 'mediaLayout'])->name('about-company.media-layout');
        Route::post('/sobre-a-empresa/upload', [AboutCompanyController::class, 'upload'])->name('about-company.upload');

        Route::get('/projetos', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projetos', [ProjectController::class, 'updatePage'])->name('projects.update-page');
        Route::post('/projetos/cards', [ProjectController::class, 'store'])->name('projects.cards.store');
        Route::put('/projetos/cards/{project}', [ProjectController::class, 'update'])->name('projects.cards.update');
        Route::delete('/projetos/cards/{project}', [ProjectController::class, 'destroy'])->name('projects.cards.destroy');
        Route::get('/projetos/cards/{project}/editar', [ProjectController::class, 'editProject'])->name('projects.project.edit');
        Route::post('/projetos/cards/{project}/imagens', [ProjectController::class, 'imageStore'])->name('projects.images.store');
        Route::put('/projetos/cards/{project}/imagens/{image}', [ProjectController::class, 'imageUpdate'])->name('projects.images.update');
        Route::delete('/projetos/cards/{project}/imagens/{image}', [ProjectController::class, 'imageDestroy'])->name('projects.images.destroy');
        Route::post('/projetos/cards/{project}/imagens/reorder', [ProjectController::class, 'mediaReorder'])->name('projects.images.reorder');
    });
});
