<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('users',UserController::class)->except(['edit','create'])->middleware(['auth:sanctum', 'abilities:admin,super-admin']);
Route::post('users',[UserController::class,'store']);
Route::post('login',[UserController::class,'login']);

Route::apiResource('roles',RoleController::class)->except(['create','edit'])->middleware(['auth:sanctum', 'abilities:admin,super-admin']);
