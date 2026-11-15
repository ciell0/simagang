<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LowonganController as AdminLowonganController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\PelamarController as AdminPelamarController;
use App\Http\Controllers\Pelamar\LowonganController as PelamarLowonganController;
use App\Http\Controllers\Pelamar\PendaftaranController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('auth.register');
});

Route::get('/dashboard', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('pelamar.dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('lowongan', AdminLowonganController::class)->except('show');
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/monitoring/export-pdf', [MonitoringController::class, 'exportPdf'])->name('monitoring.export-pdf');
    Route::get('/monitoring/export-excel', [MonitoringController::class, 'exportExcel'])->name('monitoring.export-excel');
    Route::get('/pelamar', [AdminPelamarController::class, 'index'])->name('pelamar.index');
    Route::post('/pendaftaran/{pendaftaran}/terima', [AdminPelamarController::class, 'terima'])
        ->name('pendaftaran.terima');
});

Route::middleware(['auth', 'role:pelamar'])->prefix('pelamar')->name('pelamar.')->group(function () {
    Route::view('/dashboard', 'pelamar.dashboard')->name('dashboard');
    Route::get('/lowongan', [PelamarLowonganController::class, 'index'])->name('lowongan.index');
    Route::get('/lowongan/{lowongan}', [PelamarLowonganController::class, 'show'])->name('lowongan.show');
    Route::get('/lowongan/{lowongan}/daftar', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
    Route::post('/lowongan/{lowongan}/daftar', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
