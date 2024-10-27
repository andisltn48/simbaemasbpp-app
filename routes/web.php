<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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
Route::get('/', function () {
    return redirect('/pembelian');
});

Route::group(['middleware' => ['auth']], function () {

    //dashboard
    Route::get('/pembelian', [DashboardController::class, 'indexPembelian'])->name('dashboard.index-pembelian');

    //data sampah
    Route::get('/data-sampah', [DataSampahController::class, 'index'])->name('data-sampah.index');
    Route::post('/data-sampah/submit', [DataSampahController::class, 'store'])->name('data-sampah.store');
    Route::post('/data-sampah/update/{id}', [DataSampahController::class, 'update'])->name('data-sampah.update');
    Route::get('/data-sampah/delete/{id}', [DataSampahController::class, 'destroy'])->name('data-sampah.destroy');
    Route::get('/data-sampah/detai/json', [DataSampahController::class, 'detailJson'])->name('data-sampah.detail-json');

    //data nasabah
    Route::get('/data-nasabah', [DataNasabahController::class, 'index'])->name('data-nasabah.index');
    Route::post('/data-nasabah/submit', [DataNasabahController::class, 'store'])->name('data-nasabah.store');
    Route::post('/data-nasabah/update/{id}', [DataNasabahController::class, 'update'])->name('data-nasabah.update');
    Route::get('/data-nasabah/delete/{id}', [DataNasabahController::class, 'destroy'])->name('data-nasabah.destroy');
    Route::get('/data-nasabah/detai/json', [DataNasabahController::class, 'detailJson'])->name('data-nasabah.detail-json');
});

Route::get('migrate-db', function () {

    \Illuminate\Support\Facades\Artisan::call('migrate');

    dd("Database migrated successfully.");

});
