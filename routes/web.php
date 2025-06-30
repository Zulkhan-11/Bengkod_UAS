<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

// Controller untuk Admin
// Perbaikan: Mengganti '->' menjadi '\' pada namespace
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Admin\PoliController;

// Controller untuk Dokter
// Perbaikan: Mengganti '->' menjadi '\' pada namespace
use App\Http\Controllers\Dokter\PeriksaController;
use App\Http\Controllers\Dokter\JadwalOperasiController; // Ini harus App\Http\Controllers\Dokter\JadwalOperasiController
use App\Http\Controllers\Dokter\NotifikasiController;    // Ini harus App\Http\Controllers\Dokter\NotifikasiController
use App\Http\Controllers\Dokter\ResepController;
use App\Http\Controllers\Dokter\JadwalPeriksaController; // Ini harus App\Http\Controllers\Dokter\JadwalPeriksaController

// Controller untuk Pasien
// Perbaikan: Mengganti '->' menjadi '\' pada namespace
use App\Http\Controllers\PasienController;
use App\Http\Controllers\Pasien\JanjiTemuController; // Ini harus App\Http\Controllers\Pasien\JanjiTemuController
use App\Http\Controllers\Pasien\RiwayatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role == 'dokter') {
            return redirect()->route('dokter.dashboard');
        } elseif ($user->role == 'pasien') {
            return redirect()->route('pasien.dashboard');
        }
    }
    return view('welcome');
});

Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Rute global untuk mendapatkan jadwal, sekarang ditangani oleh PasienController
// (seperti yang telah disepakati sebelumnya di PasienController.php)
Route::get('/get-jadwal-by-poli/{poliID}', [PasienController::class, 'getJadwalByPoli']);


// GRUP ROUTE UNTUK ADMIN
Route::middleware(['auth', 'can:is-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dokter', [AdminUserController::class, 'indexDokter'])->name('dokter.index');
    Route::get('/dokter/create', [AdminUserController::class, 'createDokter'])->name('dokter.create');
    Route::post('/dokter', [AdminUserController::class, 'storeDokter'])->name('dokter.store');
    Route::get('/dokter/{dokter}/edit', [AdminUserController::class, 'editDokter'])->name('dokter.edit');
    Route::put('/dokter/{dokter}', [AdminUserController::class, 'updateDokter'])->name('dokter.update');
    Route::delete('/dokter/{dokter}', [AdminUserController::class, 'destroyDokter'])->name('dokter.destroy');
    Route::get('/pasien', [AdminUserController::class, 'indexPasien'])->name('pasien.index');
    Route::get('/pasien/create', [AdminUserController::class, 'createPasien'])->name('pasien.create');
    Route::post('/pasien', [AdminUserController::class, 'storePasien'])->name('pasien.store');
    Route::get('/pasien/{pasien}/edit', [AdminUserController::class, 'editPasien'])->name('pasien.edit');
    Route::put('/pasien/{pasien}', [AdminUserController::class, 'updatePasien'])->name('pasien.update');
    Route::delete('/pasien/{pasien}', [AdminUserController::class, 'destroyPasien'])->name('pasien.destroy');
    Route::resource('poli', PoliController::class);
    Route::resource('obat', ObatController::class);
});


// GRUP ROUTE UNTUK DOKTER
Route::middleware(['auth', 'can:is-dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    Route::get('/', [HomeController::class, 'dokter'])->name('dashboard');
    Route::get('/profil', [HomeController::class, 'profil'])->name('profil');
    Route::put('/profil', [HomeController::class, 'updateProfil'])->name('profil.update');
    
    Route::get('/periksa', [PeriksaController::class, 'index'])->name('periksa.index');
    Route::get('/periksa/mulai/{periksa}', [PeriksaController::class, 'create'])->name('periksa.mulai');
    Route::post('/periksa/simpan/{periksa}', [PeriksaController::class, 'store'])->name('periksa.store');
    Route::get('/periksa/{periksa}/edit', [PeriksaController::class, 'edit'])->name('periksa.edit');
    Route::put('/periksa/{periksa}', [PeriksaController::class, 'update'])->name('periksa.update');
    
    Route::get('/riwayat', [PeriksaController::class, 'riwayat'])->name('riwayat.index');

    Route::resource('jadwal-operasi', JadwalOperasiController::class)->names('jadwal');
    Route::resource('jadwal-periksa', JadwalPeriksaController::class)->except(['show']);
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/resep/create/{periksa}', [ResepController::class, 'create'])->name('resep.create');
    Route::post('/resep', [ResepController::class, 'store'])->name('resep.store');
});


// GRUP ROUTE UNTUK PASIEN
Route::middleware(['auth', 'can:is-pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('/dashboard', [PasienController::class, 'dashboard'])->name('dashboard');
    Route::get('/resep-obat', [PasienController::class, 'resepObat'])->name('obat.index');
    Route::get('/profil-medis', [PasienController::class, 'profilMedis'])->name('profil.medis');
    
    // Rute untuk alur pendaftaran poli
    Route::get('/poli', [PasienController::class, 'daftarPoli'])->name('poli.daftar');
    Route::post('/poli', [PasienController::class, 'store'])->name('poli.store');
    
    // Rute untuk menampilkan detail pendaftaran poli
    Route::get('/poli/{id}/detail', [PasienController::class, 'showDetail'])->name('poli.detail');

    // Rute untuk riwayat pasien
    Route::get('/riwayat-pemeriksaan', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{periksa}', [RiwayatController::class, 'show'])->name('riwayat.show');
});
