<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// ── AUTH ────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── OWNER ────────────────────────────────────────────────
Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');

    // Cabang
    Route::resource('cabang', \App\Http\Controllers\Owner\CabangController::class);

    // Karyawan
    Route::resource('karyawan', \App\Http\Controllers\Owner\KaryawanController::class);

    // Presensi / Rekap
    Route::get('/rekap-kehadiran', [\App\Http\Controllers\Owner\RekapController::class, 'index'])->name('rekap.index');
    Route::get('/rekap-kehadiran/export-pdf', [\App\Http\Controllers\Owner\RekapController::class, 'exportPdf'])->name('rekap.pdf');
    Route::get('/rekap-kehadiran/export-excel', [\App\Http\Controllers\Owner\RekapController::class, 'exportExcel'])->name('rekap.excel');

    // Penggajian
    Route::get('/penggajian', [\App\Http\Controllers\Owner\PenggajianController::class, 'index'])->name('penggajian.index');
    Route::post('/penggajian/generate', [\App\Http\Controllers\Owner\PenggajianController::class, 'generate'])->name('penggajian.generate');
    Route::get('/penggajian/{penggajian}', [\App\Http\Controllers\Owner\PenggajianController::class, 'show'])->name('penggajian.show');
    Route::get('/penggajian/{penggajian}/pdf', [\App\Http\Controllers\Owner\PenggajianController::class, 'exportPdf'])->name('penggajian.pdf');
});

// ── KARYAWAN (KIOSK TERMINAL) ────────────────────────────
Route::prefix('karyawan')->name('karyawan.')->middleware(['auth', 'role:cabang'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Karyawan\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/tambah-karyawan', [\App\Http\Controllers\Karyawan\DashboardController::class, 'tambahKaryawan'])->name('tambah-karyawan');

    // Presensi
    Route::get('/presensi', [\App\Http\Controllers\Karyawan\PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi/masuk', [\App\Http\Controllers\Karyawan\PresensiController::class, 'masuk'])->name('presensi.masuk');
    Route::post('/presensi/pulang', [\App\Http\Controllers\Karyawan\PresensiController::class, 'pulang'])->name('presensi.pulang');

    // Riwayat
    Route::get('/riwayat', [\App\Http\Controllers\Karyawan\RiwayatController::class, 'index'])->name('riwayat.index');
});
