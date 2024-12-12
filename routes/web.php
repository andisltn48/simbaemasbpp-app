<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataAdminController;
use App\Http\Controllers\DataNasabahController;
use App\Http\Controllers\DataSampahController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [AuthController::class, 'loginIndex'])->name('login');
Route::post('login-validate', [AuthController::class, 'loginValidate'])->name('login-validate');
// Route::get('/register', [AuthController::class, 'registerIndex'])->name('register');

Route::get('gnrtdevlopr', [AuthController::class, 'xysgnrtsa'])->name('gnrtsa'); // to generate developer account
Route::get('gnrtadmin', [AuthController::class, 'admin'])->name('gnrtsadmin'); // to generate developer account

Route::group(['middleware' => ['auth']], function () {

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    //dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    //data sampah
    Route::get('/data-sampah', [DataSampahController::class, 'index'])->name('data-sampah.index');
    Route::post('/data-sampah/submit', [DataSampahController::class, 'store'])->name('data-sampah.store');
    Route::post('/data-sampah/update/{id}', [DataSampahController::class, 'update'])->name('data-sampah.update');
    Route::get('/data-sampah/delete/{id}', [DataSampahController::class, 'destroy'])->name('data-sampah.destroy');
    Route::get('/data-sampah/detai/json', [DataSampahController::class, 'detailJson'])->name('data-sampah.detail-json');

    //history
    Route::post('/submit-pembelian', [DataSampahController::class, 'submitPembelian'])->name('data-sampah.submit-pembelian');
    Route::get('/history-transaksi', [DataSampahController::class, 'indexHistoryTransaksi'])->name('data-sampah.index-pemasukan');
    Route::post('/submit-penjualan', [DataSampahController::class, 'submitPenjualan'])->name('data-sampah.submit-penjualan');
    Route::get('/history-sampah', [DataSampahController::class, 'indexHistorySampah'])->name('data-sampah.index-pengeluaran');
    Route::get('/history-transaksi/pdf', [DataSampahController::class, 'pdfHistoryPemasukan'])->name('data-sampah.pdf-pemasukan');
    Route::get('/history-sampah/pdf', [DataSampahController::class, 'pdfHistoryPengeluaran'])->name('data-sampah.pdf-pengeluaran');

    //data nasabah
    Route::get('/data-nasabah', [DataNasabahController::class, 'index'])->name('data-nasabah.index');
    Route::post('/data-nasabah/submit', [DataNasabahController::class, 'store'])->name('data-nasabah.store');
    Route::post('/data-nasabah/update/{id}', [DataNasabahController::class, 'update'])->name('data-nasabah.update');
    Route::get('/data-nasabah/delete/{id}', [DataNasabahController::class, 'destroy'])->name('data-nasabah.destroy');
    Route::get('/data-nasabah/detai/json', [DataNasabahController::class, 'detailJson'])->name('data-nasabah.detail-json');

    //data admin
    Route::get('/data-admin', [DataAdminController::class, 'index'])->name('data-admin.index');
    Route::post('/data-admin/submit', [DataAdminController::class, 'store'])->name('data-admin.store');
    Route::post('/data-admin/update/{id}', [DataAdminController::class, 'update'])->name('data-admin.update');
    Route::get('/data-admin/delete/{id}', [DataAdminController::class, 'destroy'])->name('data-admin.destroy');
    Route::get('/data-admin/detai/json', [DataAdminController::class, 'detailJson'])->name('data-admin.detail-json');
});

Route::get('migrate-db', function () {

    \Illuminate\Support\Facades\Artisan::call('migrate');

    dd("Database migrated successfully.");

});
Route::get('migrate-db-fresh', function () {

    \Illuminate\Support\Facades\Artisan::call('migrate:fresh');

    dd("Database migrated successfully.");

});
Route::get('repair-data-penjualan', function () {

    \Illuminate\Support\Facades\Artisan::call('repair-penjualan');

    dd("Database migrated successfully.");

});

Route::get('clear-config', function () {
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');   
});