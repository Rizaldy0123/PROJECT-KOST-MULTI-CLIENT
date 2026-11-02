<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyController; // 1. Import PropertyController

// Halaman utama otomatis diarahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute untuk pengguna yang BELUM login (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Rute untuk pengguna yang SUDAH login (Auth)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard Pengelola Kost (Admin Client)
    Route::get('/admin/dashboard', [DashboardController::class, 'adminIndex'])->name('admin.dashboard');

    // 2. Rute CRUD Properti Kost (Admin Client)
    Route::resource('admin/properties', PropertyController::class, [
        'names' => 'admin.properties'
    ]);

    // Dashboard Penghuni
    Route::get('/penghuni/dashboard', function () {
        return 'Selamat Datang di Dashboard Penghuni!';
    })->name('penghuni.dashboard');

    // Dashboard Utama / Super Admin
    Route::get('/dashboard', function () {
        return 'Selamat Datang di Dashboard Platform Utama!';
    })->name('dashboard');
});