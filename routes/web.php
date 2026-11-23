<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\OrganizationalStructureController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KegiatanPesertaController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\BackupController;


// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// DEBUG Route (temporary - untuk diagnosa foto struktur organisasi)
Route::get('/debug/photos', [DebugController::class, 'photos'])->name('debug.photos');

// Authentication routes (NO REGISTER)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Logout (accessible when authenticated)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (need authentication)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

    // API Monitoring Data (untuk auto-refresh)
    Route::get('/api/monitoring-data', [DashboardController::class, 'monitoringData'])
    ->name('api.monitoring-data');

    // Route::get('/dashboard', function () {
    //     $totalPengurus = \App\Models\User::where('role', 'pengurus')->count();
    //     $totalAnggota = \App\Models\User::where('role', 'anggota')->count();
    //     $totalAktif = \App\Models\User::where('is_active', true)->count();
    //     $anggotaTerbaru = \App\Models\User::latest()->take(5)->get();
    //     $totalDokumen = 5;

    //     return view('dashboard', compact(
    //         'totalPengurus',
    //         'totalAnggota',
    //         'totalAktif',
    //         'totalDokumen',
    //         'anggotaTerbaru'
    //     ));
    // })->name('dashboard');

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.photo');
    });

    // Anggota Management Routes (Hanya Admin & Pengurus)
    Route::middleware('role:admin')->prefix('anggota')->name('anggota.')->group(function () {
        Route::get('/', [AnggotaController::class, 'index'])->name('index');
        Route::get('/create', [AnggotaController::class, 'create'])->name('create');
        Route::post('/', [AnggotaController::class, 'store'])->name('store');
        Route::get('/{user}', [AnggotaController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [AnggotaController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AnggotaController::class, 'update'])->name('update');
        Route::delete('/{user}', [AnggotaController::class, 'destroy'])->name('destroy');

        // Additional routes
        Route::post('/{user}/toggle-status', [AnggotaController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{user}/reset-password', [AnggotaController::class, 'resetPassword'])->name('reset-password');
    });

    // routes/web.php
    Route::controller(DokumenController::class)->prefix('dokumen')->group(function () {
        Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
        Route::get('/create', [DokumenController::class, 'create'])->name('dokumen.create');
        Route::post('/', [DokumenController::class, 'store'])->name('dokumen.store');
        Route::get('/{id}', [DokumenController::class, 'show'])->name('dokumen.show');
        Route::get('/download/{id}', [DokumenController::class, 'download'])->name('dokumen.download');
        Route::post('/bulk-download', [DokumenController::class, 'bulkDownload'])->name('dokumen.bulk.download');
        Route::get('/preview/{id}', [DokumenController::class, 'preview'])->name('dokumen.preview');
        Route::delete('/{id}', [DokumenController::class, 'destroy'])->name('dokumen.destroy');
        Route::get('/stats', [DokumenController::class, 'stats'])->name('dokumen.stats');
    });

    // Pengumuman
    Route::resource('announcements', AnnouncementController::class)->except(['edit', 'update']);

    // Home1 routes
    Route::get('/home1/baca/{id}', [AnnouncementController::class, 'baca'])->name('home1.baca');

    Route::resource('organizational-structure', OrganizationalStructureController::class);

    // Routes untuk Kegiatan
    Route::prefix('kegiatan')->name('kegiatan.')->group(function () {
        Route::get('/', [KegiatanController::class, 'index'])->name('index');
        Route::get('/create', [KegiatanController::class, 'create'])->name('create');
        Route::post('/', [KegiatanController::class, 'store'])->name('store');
        Route::get('/{id}', [KegiatanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [KegiatanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [KegiatanController::class, 'update'])->name('update');
        Route::delete('/{id}', [KegiatanController::class, 'destroy'])->name('destroy');

        // Upload foto
        Route::post('/{id}/upload-foto', [KegiatanController::class, 'uploadFoto'])->name('upload-foto');
        Route::delete('/{id}/foto/{fotoId}', [KegiatanController::class, 'hapusFoto'])->name('hapus-foto');

        // Update status
        Route::post('/{id}/update-status', [KegiatanController::class, 'updateStatus'])->name('update-status');
    });

    // Routes untuk Peserta Kegiatan
    Route::prefix('kegiatan-peserta')->name('kegiatan-peserta.')->group(function () {
        Route::post('/{id}/daftar', [KegiatanPesertaController::class, 'daftar'])->name('daftar');
        Route::delete('/{id}/batalkan', [KegiatanPesertaController::class, 'batalkanPendaftaran'])->name('batalkan');
        Route::post('/{kegiatanId}/{userId}/konfirmasi', [KegiatanPesertaController::class, 'konfirmasiPeserta'])->name('konfirmasi');
        Route::post('/{kegiatanId}/{userId}/kehadiran', [KegiatanPesertaController::class, 'updateKehadiran'])->name('update-kehadiran');
    });

    // Backup Routes
    Route::prefix('backup')->name('backup.')->middleware(['auth'])->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::post('/', [BackupController::class, 'store'])->name('store');
        Route::get('/status/{id}', [BackupController::class, 'status'])->name('status');
        Route::get('/history', [BackupController::class, 'history'])->name('history');
        Route::get('/download/{id}', [BackupController::class, 'download'])->name('download');
        Route::delete('/{id}', [BackupController::class, 'destroy'])->name('destroy');
    });

});
