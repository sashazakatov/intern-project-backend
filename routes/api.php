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

Route::prefix('/users')->name('users.')->group(function(){

    //создания пользователя
    Route::post('/create-user', [ UserController::class, 'add' ])
    ->middleware([ 'CheckUserRole' ])
    ->name('create-user');

    //удаления пользователя
    Route::delete('delete-user', [ UserController::class, 'delete' ])
    ->name('delete-user');

    //обновление пользователя
    Route::put('update-user-info', [ UserController::class, 'updateUserInfo' ]);

    //возрошает информацию о пользователе
    Route::get('/user-info', [ UserController::class, 'getUserInfo' ]);

    //регистрация пользователя
    Route::post('/register', [AuthController::class, 'register' ])
    ->name('register');

    //Аутентификация пользователя
    Route::post('/login', [AuthController::class, 'login'])
    ->name('login');
});