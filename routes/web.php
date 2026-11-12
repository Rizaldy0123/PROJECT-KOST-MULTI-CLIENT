<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController; // Tambahkan import RegisterController
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\PenghuniDashboardController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // --- RUTE REGISTRASI (Ditambahkan di sini agar bisa diakses saat belum login) ---
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
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

    // CRUD Tagihan
    Route::resource('admin/invoices', InvoiceController::class, [
        'names' => 'admin.invoices'
    ]);

    // CRUD Pembayaran
    Route::resource('admin/payments', PaymentController::class, [
        'names' => 'admin.payments'
    ])->only(['index', 'create', 'store', 'destroy']);

    // Rute Kuitansi Pembayaran
    Route::get('admin/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('admin.payments.receipt');

    // CRUD Tiket Perbaikan
    Route::resource('admin/maintenance', MaintenanceController::class, [
        'names' => 'admin.maintenance'
    ])->parameters([
        'maintenance' => 'maintenance'
    ]);

    // Dashboard Penghuni
    Route::get('/penghuni/dashboard', [PenghuniDashboardController::class, 'index'])
    ->name('penghuni.dashboard');
    
    // Pengajuan Keluhan Penghuni
    Route::post('/penghuni/keluhan', [PenghuniDashboardController::class, 'store'])
    ->name('penghuni.keluhan.store');

    // Mengarahkan /dashboard utama ke DashboardController
    Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard');
});