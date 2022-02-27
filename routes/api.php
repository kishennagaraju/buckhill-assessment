<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
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

Route::group(
    [
        'prefix' => 'admin'
    ],
    function () {
        Route::post('/create', [AuthController::class, 'store'])->name('admin.admin.create');
        Route::post('/login', [AuthController::class, 'login'])->name('admin.admin.login');

        Route::group(['middleware' => 'basic.auth'], function() {
            Route::get('/user-listing', [AdminUserController::class, 'index'])->name('admin.list.user');
            Route::delete('/user-delete/{uuid}', [AdminUserController::class, 'deleteUser'])->name('admin.delete.user');
        });
    }
);

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
