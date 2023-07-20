<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/user')->name('user.')->group(function(){
    Route::post('/create-user', [ UserController::class, 'add' ])
    ->name('create-user');

    Route::post('/register', [AuthController::class, 'register' ])
    ->name('register');

    Route::post('/login', [AuthController::class, 'login'])
    ->name('login');
});