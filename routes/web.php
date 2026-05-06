<?php

use App\Http\Controllers\DocumentController;
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

Route::get('/', [DocumentController::class, 'index'])->name('home');

Route::get('/d/{slug}', [QrScanController::class, 'show'])
    ->where('slug', '[A-Za-z0-9_-]+')
    ->name('scan.show');

Route::prefix('documents')->name('documents.')->group(function () {
    Route::get('/', [DocumentController::class, 'index'])->name('index');
    Route::get('/create', [DocumentController::class, 'create'])->name('create');
    Route::post('/', [DocumentController::class, 'store'])->name('store');
    Route::get('/print', [DocumentController::class, 'print'])->name('print');
    Route::get('/{document}/print', [DocumentController::class, 'printSingle'])->name('print.single');
    Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('edit');
    Route::put('/{document}', [DocumentController::class, 'update'])->name('update');
    Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy');
    Route::get('/{document}/qr', [DocumentController::class, 'qr'])->name('qr');
});
