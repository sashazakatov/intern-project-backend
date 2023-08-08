<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\DeviceController;

Route::prefix('/users')->middleware([ 'auth:api' ])->name('users.')->group(function(){

    //возрошает пользователей
    Route::get('/', [UserController::class, 'getUser'])
    ->name('getUser');

    //создания пользователя
    Route::post('/create-user', [ UserController::class, 'add' ])
    ->middleware([ 'CheckUserRole' ])
    ->name('create-user');

    //удаления пользователя
    Route::delete('delete-user', [ UserController::class, 'delete' ])
    ->name('delete-user');

    //обновление пользователя
    Route::put('update-user-info', [ UserController::class, 'updateUserInfo' ]);

    //обновление аватарки
    Route::post('/update-avatar', [UserController::class, 'updateAvatar'])
    ->name('update-avatar');

    //возрошает информацию о пользователе
    Route::get('/user-info', [ UserController::class, 'getUserInfo' ]);

    //регистрация пользователя
    Route::post('/register', [AuthController::class, 'register' ])
    ->name('register');

    // Аутентификация пользователя
    Route::post('/login', [AuthController::class, 'login'])
    ->name('login');
});

Route::prefix('users/check-email')->name('users.')->group(function(){
    // Проверки емейла на уникальность
    Route::post('/', [UserController::class, 'checkEmail'])
    ->name('check.email');
});

//возвращает аватарки
Route::get('/standard-avatars', [AvatarController::class, 'getStandardAvatars']);

//Эндпоинты дивайсов
Route::prefix('/devices')->name('devices.')->group(function(){

    //Создание дивайса
    Route::post('/create', [DeviceController::class, 'create']);

    //Редактирование дивайса
    Route::put('/edit/{id}', [DeviceController::class, 'edit']);

    //Удаление дивайса
    Route::delete('/delete/{id}', [DeviceController::class, 'delete']);
});