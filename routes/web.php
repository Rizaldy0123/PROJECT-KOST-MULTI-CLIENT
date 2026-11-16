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
use App\Http\Controllers\PenghuniSewaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\NotifikasiController;

Route::get('/', function () {
    return view('welcome');
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

    Route::prefix('admin')->name('admin.')->group(function () {

        // Halaman daftar notifikasi
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])
            ->name('notifikasi.index');

        // Halaman form kirim notifikasi
        Route::get('/notifikasi/create', [NotifikasiController::class, 'create'])
            ->name('notifikasi.create');

        // Proses mengirim notifikasi
        Route::post('/notifikasi', [NotifikasiController::class, 'store'])
            ->name('notifikasi.store');
    });

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
    
    Route::post('/notifikasi/{id}/baca', [PenghuniDashboardController::class, 'bacaNotifikasi'])
        ->name('penghuni.notifikasi.baca');

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

    // ===== Area Penghuni (sidebar) =====
    // Halaman utama: Informasi & Sewa Kost
    Route::get('/penghuni/dashboard', [PenghuniDashboardController::class, 'index'])
        ->name('penghuni.dashboard');

    // CRUD Sewa Kost oleh penghuni
    Route::resource('penghuni/sewa', PenghuniSewaController::class, [
        'names' => 'penghuni.sewa',
    ])->parameters(['sewa' => 'contract'])
        ->only(['create', 'store', 'edit', 'update', 'destroy']);

    // Menu sidebar lainnya
    Route::get('/penghuni/tagihan', [PenghuniDashboardController::class, 'tagihan'])->name('penghuni.tagihan');
    Route::get('/penghuni/pembayaran', [PenghuniDashboardController::class, 'pembayaran'])->name('penghuni.pembayaran');
    Route::get('/penghuni/keluhan', [PenghuniDashboardController::class, 'keluhan'])->name('penghuni.keluhan');
    Route::post('/penghuni/keluhan', [PenghuniDashboardController::class, 'store'])->name('penghuni.keluhan.store');
    Route::get('/penghuni/notifikasi', [PenghuniDashboardController::class, 'notifikasi'])->name('penghuni.notifikasi');

    // Mengarahkan /dashboard utama ke DashboardController
    Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard');
});
