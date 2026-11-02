<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ContractController; // Tambahkan import ContractController

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard Pengelola Kost (Admin Client)
    Route::get('/admin/dashboard', [DashboardController::class, 'adminIndex'])->name('admin.dashboard');

    // CRUD Properti Kost
    Route::resource('admin/properties', PropertyController::class, [
        'names' => 'admin.properties'
    ]);

    // CRUD Tipe Kamar
    Route::resource('admin/room-types', RoomTypeController::class, [
        'names' => 'admin.room-types'
    ]);

    // CRUD Unit Kamar
    Route::resource('admin/rooms', RoomController::class, [
        'names' => 'admin.rooms'
    ]);

    // CRUD Kontrak Sewa
    Route::resource('admin/contracts', ContractController::class, [
        'names' => 'admin.contracts'
    ]);

    // Dashboard Penghuni
    Route::get('/penghuni/dashboard', function () {
        return 'Selamat Datang di Dashboard Penghuni!';
    })->name('penghuni.dashboard');

    // Mengarahkan /dashboard utama ke DashboardController
    Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard');
});