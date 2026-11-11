<?php

use App\Http\Controllers\AreaSiswaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CekPresensiController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardGuruController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\PesertaJadwalController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/cek-presensi', CekPresensiController::class)
    ->middleware('throttle:20,1')
    ->name('cek-presensi');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', DashboardController::class);

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', DashboardAdminController::class);

        Route::resource('mapel', MapelController::class)->except('show');
        Route::resource('guru', GuruController::class)->except('show');
        Route::resource('siswa', SiswaController::class)->except('show');
        Route::resource('jadwal', JadwalController::class)->except('show');
        Route::get('jadwal/{jadwal}/peserta', [PesertaJadwalController::class, 'index'])->name('jadwal.peserta');
        Route::post('jadwal/{jadwal}/peserta', [PesertaJadwalController::class, 'store'])->name('jadwal.peserta.store');
        Route::delete('jadwal/{jadwal}/peserta/{jadwalSiswa}', [PesertaJadwalController::class, 'destroy'])->name('jadwal.peserta.destroy');
        Route::get('presensi', [PresensiController::class, 'index'])->name('presensi.index');
        Route::post('presensi', [PresensiController::class, 'store'])->name('presensi.store');
    });

    Route::middleware('role:guru')->prefix('guru')->group(function () {
        Route::get('/dashboard', DashboardGuruController::class);
        Route::get('presensi', [PresensiController::class, 'index'])->name('guru.presensi.index');
        Route::post('presensi', [PresensiController::class, 'store'])->name('guru.presensi.store');
    });

    Route::middleware('role:siswa')->prefix('siswa')->group(function () {
        Route::get('/dashboard', [AreaSiswaController::class, 'dashboard']);
        Route::get('/riwayat', [AreaSiswaController::class, 'riwayat'])->name('siswa.riwayat');
    });
});