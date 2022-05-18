<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginSPAController;

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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/register',[LoginSPAController::class,'register']);
Route::post('/login',[LoginSPAController::class,'login']);
Route::get('/logout',[LoginSPAController::class,'logout'])->name('logout');
Route::get('/login',[LoginSPAController::class,'loginForm'])->name('login');
