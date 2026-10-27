<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PimpinanDataSuaraController;
use App\Http\Controllers\PimpinanRekapController;
use App\Http\Controllers\PimpinanClusteringController;
use App\Http\Controllers\PimpinanPerbandinganController;

use App\Http\Controllers\Public\BerandaController;
use App\Http\Controllers\Public\DataSuaraController;
use App\Http\Controllers\Public\HasilKlasterController;
use App\Http\Controllers\Public\PerbandinganController as PublicPerbandinganController;
use App\Http\Controllers\Public\TentangController;

use App\Http\Controllers\Admin\ManajemenAkunController;
use App\Http\Controllers\Admin\RiwayatAktivitasController;

Route::get('/', [BerandaController::class, 'index'])
    ->name('beranda');

Route::get('/data-suara', [DataSuaraController::class, 'index'])
    ->name('data-suara');

Route::get('/hasil-klaster', [HasilKlasterController::class, 'index'])
    ->name('hasil-klaster');

Route::get('/perbandingan', [PublicPerbandinganController::class, 'index'])
    ->name('perbandingan');

Route::get('/tentang', [TentangController::class, 'index'])
    ->name('tentang');


Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PIMPINAN
    |--------------------------------------------------------------------------
    */

    Route::prefix('pimpinan')->name('pimpinan.')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [DashboardController::class, 'pimpinan'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | DATA SUARA
        |--------------------------------------------------------------------------
        */

        Route::get('/data-suara', [PimpinanDataSuaraController::class, 'index'])
            ->name('data-suara.index');

        Route::get('/data-suara/export-excel', [PimpinanDataSuaraController::class, 'exportExcel'])
            ->name('data-suara.export.excel');

        Route::get('/data-suara/export-pdf', [PimpinanDataSuaraController::class, 'exportPdf'])
            ->name('data-suara.export.pdf');


        /*
        |--------------------------------------------------------------------------
        | REKAPITULASI
        |--------------------------------------------------------------------------
        */

        Route::get('/rekapitulasi', [PimpinanRekapController::class, 'index'])
            ->name('rekap.index');

        Route::get('/rekapitulasi/export-excel', [PimpinanRekapController::class, 'exportExcel'])
            ->name('rekap.export.excel');

        Route::get('/rekapitulasi/export-pdf', [PimpinanRekapController::class, 'exportPdf'])
            ->name('rekap.export.pdf');


        /*
        |--------------------------------------------------------------------------
        | K-MEANS CLUSTERING
        |--------------------------------------------------------------------------
        */

        Route::get('/hasil-clustering', [PimpinanClusteringController::class, 'index'])
            ->name('clustering.index');

        Route::get('/hasil-clustering/export-excel', [PimpinanClusteringController::class, 'exportExcel'])
            ->name('clustering.export.excel');

        Route::get('/hasil-clustering/export-pdf', [PimpinanClusteringController::class, 'exportPdf'])
            ->name('clustering.export.pdf');


        /*
        |--------------------------------------------------------------------------
        | PERBANDINGAN TREN
        |--------------------------------------------------------------------------
        */

        Route::get('/perbandingan-tren', [PimpinanPerbandinganController::class, 'index'])
            ->name('perbandingan.index');

        Route::get('/perbandingan-tren/export-excel', [PimpinanPerbandinganController::class, 'exportExcel'])
            ->name('perbandingan.export.excel');

        Route::get('/perbandingan-tren/export-pdf', [PimpinanPerbandinganController::class, 'exportPdf'])
            ->name('perbandingan.export.pdf');
    });


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD REDIRECT
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {

        $role = auth()->user()->role;

        if ($role === 'pimpinan') {
            return redirect()->route('pimpinan.dashboard');
        }

        return redirect()->route('admin.dashboard');
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | ADMIN DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->name('admin.dashboard');


    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')->name('admin.')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | MANAJEMEN AKUN
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'manajemen-akun',
            ManajemenAkunController::class
        )->parameters([
            'manajemen-akun' => 'user',
        ]);


        /*
        |--------------------------------------------------------------------------
        | RIWAYAT AKTIVITAS
        |--------------------------------------------------------------------------
        */

        Route::get('/riwayat-aktivitas', [RiwayatAktivitasController::class, 'index'])
            ->name('riwayat.index');


        /*
        |--------------------------------------------------------------------------
        | REKAP SUARA
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/rekap-suara',
            [\App\Http\Controllers\Admin\RekapController::class, 'index']
        )->name('rekap.index');

        Route::post(
            '/rekap-suara/sinkron/{tahun}',
            [\App\Http\Controllers\Admin\RekapController::class, 'sinkronisasi']
        )->name('rekap.sinkron');

        Route::get(
            '/rekap-suara/export-excel/{tahun}',
            [\App\Http\Controllers\Admin\RekapController::class, 'exportExcel']
        )->name('rekap.export.excel');

        Route::get(
            '/rekap-suara/export-pdf/{tahun}',
            [\App\Http\Controllers\Admin\RekapController::class, 'exportPdf']
        )->name('rekap.export.pdf');


        /*
        |--------------------------------------------------------------------------
        | RINCIAN SUARA
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/rincian-suara',
            [\App\Http\Controllers\Admin\RincianSuaraController::class, 'index']
        )->name('rincian.index');

        Route::get(
            '/rincian-suara/create',
            [\App\Http\Controllers\Admin\RincianSuaraController::class, 'create']
        )->name('rincian.create');

        Route::post(
            '/rincian-suara',
            [\App\Http\Controllers\Admin\RincianSuaraController::class, 'store']
        )->name('rincian.store');

        Route::get(
            '/rincian-suara/{tahun}/{id}/edit',
            [\App\Http\Controllers\Admin\RincianSuaraController::class, 'edit']
        )->name('rincian.edit');

        Route::put(
            '/rincian-suara/{tahun}/{id}',
            [\App\Http\Controllers\Admin\RincianSuaraController::class, 'update']
        )->name('rincian.update');

        Route::delete(
            '/rincian-suara/{tahun}/{id}',
            [\App\Http\Controllers\Admin\RincianSuaraController::class, 'destroy']
        )->name('rincian.destroy');


        /*
        |--------------------------------------------------------------------------
        | K-MEANS CLUSTERING
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/proses-clustering',
            [\App\Http\Controllers\Admin\ClusteringController::class, 'index']
        )->name('clustering.index');

        Route::post(
            '/proses-clustering/jalankan',
            [\App\Http\Controllers\Admin\ClusteringController::class, 'jalankan']
        )->name('clustering.jalankan');


        /*
        |--------------------------------------------------------------------------
        | PERBANDINGAN TREN
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/perbandingan-tren',
            [\App\Http\Controllers\Admin\PerbandinganController::class, 'index']
        )->name('perbandingan.index');

        Route::get(
            '/perbandingan-tren/export-excel',
            [\App\Http\Controllers\Admin\PerbandinganController::class, 'exportExcel']
        )->name('perbandingan.export.excel');

        Route::get(
            '/perbandingan-tren/export-pdf',
            [\App\Http\Controllers\Admin\PerbandinganController::class, 'exportPdf']
        )->name('perbandingan.export.pdf');
    });
});


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
