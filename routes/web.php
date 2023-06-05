<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [RegionController::class, 'dashboard'])->name('dashboard');

// pincode
Route::match(['get', 'post'], '/pincode', [RegionController::class, 'pincode'])->name('pincode');
Route::match(['get', 'post'], '/edit-pincode/{id}', [RegionController::class, 'updatePincode'])->name('updatePincode');

Route::get('/check-dis', [RegionController::class, 'checkdis'])->name('checkdis');
