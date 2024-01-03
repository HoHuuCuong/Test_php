<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/shop', [HomeController::class, 'index']);
Route::post('/add-to-cart', [HomeController::class, 'add']);
Route::post('/update-cart', [HomeController::class, 'updateCart']);
Route::post('/delete-cart', [HomeController::class, 'deleteCart']);
