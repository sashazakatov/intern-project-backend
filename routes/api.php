<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\GroupController;

Route::prefix('/users')->name('users.')->group(function(){
  
    //возрошает пользователей
    Route::get('/', [UserController::class, 'getUser'])
    ->middleware([ 'auth:api', 'CheckUserRole' ])
    ->name('getUser');

    //создания пользователя
    Route::post('/create-user', [ UserController::class, 'add' ])
    ->middleware([ 'auth:api', 'CheckUserRole' ])
    ->name('create-user');

    //удаления пользователя
    Route::delete('/delete-user', [ UserController::class, 'delete' ])
    ->middleware([ 'auth:api', 'CheckUserRole' ])
    ->name('delete-user');

    //обновление пользователя
    Route::put('/update-user-info', [ UserController::class, 'updateUserInfo' ])
    ->middleware([ 'auth:api' ]);

    //обновление аватарки
    Route::post('/update-avatar', [UserController::class, 'updateAvatar'])
    ->middleware([ 'auth:api' ])
    ->name('update-avatar');

    //возрошает информацию о пользователе
    Route::get('/user-info', [ UserController::class, 'getUserInfo' ])
    ->middleware([ 'auth:api' ]);

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

    //Возрошает дивайсы
    Route::get('/', [DeviceController::class, 'getDevices'])
    ->middleware(['auth:api', 'CheckCustomerRole'])
    ->name('get-device');

    //Создание дивайса
    Route::post('/create', [DeviceController::class, 'create'])
    ->middleware([ 'auth:api', 'CheckUserRole' ])
    ->name('create');

    //Редактирование дивайса
    Route::put('/edit', [DeviceController::class, 'edit'])
    ->middleware([ 'auth:api', 'CheckUserRole' ])
    ->name('edit');

    //Удаление дивайса
    Route::delete('/delete', [DeviceController::class, 'delete'])
    ->middleware([ 'auth:api', 'CheckUserRole' ])
    ->name('delete');
});

Route::prefix('/groups')->name('groups.')->group(function () {

    //возрощает группы
    Route::get('', [GroupController::class, 'get'])
    ->middleware([ 'auth:api', 'RegionalAdminMiddleware' ])
    ->name('get');

    // создания новой групу
    Route::post('/create', [GroupController::class, 'create'])
    ->middleware([ 'auth:api', 'RegionalAdminMiddleware' ])
    ->name('create');

    //удаление гпупп
    Route::delete('delete', [GroupController::class, 'delete'])
    ->middleware([ 'auth:api', 'RegionalAdminMiddleware', 'CheckGroupAccess' ])
    ->name('delete');
    
    //обноелвение групп
    Route::put('edit', [GroupController::class, 'edit'])
    ->middleware([ 'auth:api', 'RegionalAdminMiddleware', 'CheckGroupAccess' ])
    ->name('edit');
});