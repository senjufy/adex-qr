<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectViewerController;
use App\Http\Controllers\QrScanController;
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

/*
|--------------------------------------------------------------------------
| PERMANENT PUBLIC ROUTES (DO NOT CHANGE)
| These routes are encoded into physical QR labels. Changing these 
| will break existing labels in the field.
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/d/{slug}', [QrScanController::class, 'show'])
    ->where('slug', '[A-Za-z0-9_-]+')
    ->name('scan.show');

Route::get('/p/{slug}', [ProjectViewerController::class, 'show'])
    ->where('slug', '[A-Za-z0-9_-]+')
    ->name('project.show');

Route::middleware(['admin.gate'])->group(function () {
    Route::get('/', [ProjectController::class, 'dashboard'])->name('home');

    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::get('/print', [ProjectController::class, 'print'])->name('print');
        Route::get('/{project}/print', [ProjectController::class, 'printSingle'])->name('print.single');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
        Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
        Route::get('/{project}/qr', [ProjectController::class, 'qr'])->name('qr');
    });

    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::get('/create', [DocumentController::class, 'create'])->name('create');
        Route::post('/', [DocumentController::class, 'store'])->name('store');
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('edit');
        Route::put('/{document}', [DocumentController::class, 'update'])->name('update');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');
    });
});
