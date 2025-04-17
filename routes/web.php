<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\BankMiniController;


// ====== AUTHENTICATION ======
// Menampilkan halaman login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Memproses login
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ====== ADMIN DASHBOARD ======
// Menampilkan halaman dashboard admin
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

// Menambahkan user baru (diproses melalui form di halaman dashboard admin)
Route::post('/admin/tambah-user', [AdminController::class, 'tambahUser'])->name('admin.tambah.user');

//Menampilkan user
Route::get('/admin/tampilkan-user', [AdminController::class, 'tampilkanUser'])->name('user.index');

// Redirect dari /admin ke /admin/dashboard
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

Route::delete('/admin/hapus-user/{id}', [AdminController::class, 'hapusUser'])->name('admin.hapus.user');
// ====== END ADMIN DASHBOARD ======

// ====== FITUR SISWA ======
// Top-up saldo siswa
Route::post('/siswa/topup', [SiswaController::class, 'topUp'])->name('siswa.topup');
// Tarik saldo siswa
Route::post('/siswa/tarik', [SiswaController::class, 'tarikSaldo'])->name('siswa.tarik');
// Transfer saldo siswa
Route::get('/transfer', [SiswaController::class, 'showTransferForm'])->name('transfer.form');
Route::post('/transfer', [SiswaController::class, 'transferSaldo'])->name('transfer.saldo');

Route::get('/siswa/print-transaksi', [SiswaController::class, 'cetakRiwayat'])->name('siswa.cetak.riwayat');

// Menampilkan riwayat transaksi untuk semua pengguna
Route::get('/riwayat-transaksi', [TransactionController::class, 'riwayatTransaksi'])->name('riwayat.transaksi');


Route::middleware('auth')->group(function () {
    Route::get('/admin', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/bank_mini', [BankMiniController::class, 'index'])->name('bankmini.dashboard');
    Route::get('/siswa', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
});
