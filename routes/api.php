<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Http\Controllers\User\UserController as UserController;
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

Route::group(
    [
        'prefix' => 'admin'
    ],
    function () {
        Route::post('/create', [AdminUserController::class, 'store'])->name('admin.create');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login');

        Route::group(['middleware' => 'basic.auth.admin'], function() {
            Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
            Route::get('/user-listing', [AdminUserController::class, 'index'])->name('admin.list.user');
            Route::delete('/user-delete/{uuid}', [AdminUserController::class, 'deleteUser'])->name('admin.delete.user');
        });
    }
);

Route::group(
    [
        'prefix' => 'user'
    ],
    function () {
        Route::post('/create', [UserController::class, 'storeUser'])->name('user.create');
        Route::post('/login', [UserAuthController::class, 'login'])->name('user.login');

        Route::group(['middleware' => 'basic.auth'], function() {
            Route::get('/', [UserController::class, 'show'])->name('user.details');
            Route::delete('/', [UserController::class, 'delete'])->name('user.delete');
            Route::get('/logout', [UserAuthController::class, 'logout'])->name('user.logout');
        });
    }
);

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
