<?php

use App\Http\Controllers\DoaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\ShalatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Nurul Quran (Portal Islami Integrasi eQuran.id)
|--------------------------------------------------------------------------
*/

// Beranda Utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Layanan 01: Al-Qur'an Digital
Route::prefix('quran')->name('quran.')->group(function () {
    Route::get('/', [QuranController::class, 'index'])->name('index');
    Route::get('/{nomor}', [QuranController::class, 'show'])->whereNumber('nomor')->name('show');
    Route::get('/{nomor}/tafsir', [QuranController::class, 'tafsir'])->whereNumber('nomor')->name('tafsir');
});

// Layanan 02: Doa Harian
Route::prefix('doa')->name('doa.')->group(function () {
    Route::get('/', [DoaController::class, 'index'])->name('index');
    Route::get('/{id}', [DoaController::class, 'show'])->whereNumber('id')->name('show');
});

// Layanan 03: Jadwal Sholat (Pengayaan)
Route::prefix('shalat')->name('shalat.')->group(function () {
    Route::get('/', [ShalatController::class, 'index'])->name('index');
    Route::get('/kabkota-ajax', [ShalatController::class, 'getKabKota'])->name('kabkota');
});

// Dashboard & Profile (Breeze Auth)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
