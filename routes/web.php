<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\LavoroController;
use App\Http\Controllers\Backend\PrinterController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\ProjectController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', fn() => redirect()->route('login'));

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Backend routes (auth required)
Route::prefix('backend')->name('backend.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User management
    Route::resource('users', UserController::class)->except(['show']);

    // Printer management
    Route::resource('printers', PrinterController::class)->except(['show']);

    // Customer management
    Route::resource('customers', CustomerController::class);

    // Lavori management
    Route::resource('lavori', LavoroController::class)->parameters(['lavori' => 'lavoro']);
    Route::post('lavori/{lavoro}/assign-printer', [LavoroController::class, 'assignPrinter'])->name('lavori.assign-printer');
    Route::delete('lavori/{lavoro}/release-printer', [LavoroController::class, 'releasePrinter'])->name('lavori.release-printer');

    // Project management
    Route::resource('projects', ProjectController::class);
    Route::delete('projects/{project}/files/{file}', [ProjectController::class, 'destroyFile'])->name('projects.files.destroy');
    Route::get('projects/{project}/files/{file}/download', [ProjectController::class, 'downloadFile'])->name('projects.files.download');
});
