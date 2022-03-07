<?php

    use App\Http\Controllers\Admin\UserController as AdminUserController;
    use App\Http\Controllers\AdminAuthController;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\BlogController;
    use App\Http\Controllers\BrandsController;
    use App\Http\Controllers\CategoriesController;
    use App\Http\Controllers\FilesController;
    use App\Http\Controllers\OrdersController;
    use App\Http\Controllers\OrderStatusesController;
    use App\Http\Controllers\PaymentsController;
    use App\Http\Controllers\ProductsController;
    use App\Http\Controllers\PromotionsController;
    use App\Http\Controllers\UserController;
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
            Route::put('/user-edit/{uuid}', [AdminUserController::class, 'update'])->name('admin.edit.user');
            Route::delete('/user-delete/{uuid}', [AdminUserController::class, 'delete'])->name('admin.delete.user');
        });
    }
);

Route::group(
    [
        'prefix' => 'user'
    ],
    function () {
        Route::post('/create', [UserController::class, 'storeUser'])->name('user.create');
        Route::post('/login', [AuthController::class, 'login'])->name('user.login');

        Route::group(['middleware' => 'basic.auth'], function() {
            Route::get('/', [UserController::class, 'show'])->name('user.details');
            Route::get('/orders', [UserController::class, 'getOrdersForUser'])->name('user.orders');
            Route::get('/logout', [AuthController::class, 'logout'])->name('user.logout');
            Route::put('/{uuid}', [UserController::class, 'update'])->name('user.edit');
            Route::delete('/{uuid}', [UserController::class, 'delete'])->name('user.delete');
        });
    }
);

Route::group(
    ['prefix' => 'main'],
    function() {
        Route::apiResource('promotions', PromotionsController::class)->only(['index']);
        Route::apiResource('blog', BlogController::class)->only(['index', 'show'])->parameters([
            'blog' => 'uuid'
        ]);
    }
);

Route::apiResource('categories', CategoriesController::class)->parameters(['categories' => 'uuid']);
Route::apiResource('brands', BrandsController::class)->parameters(['brands' => 'uuid']);
Route::apiResource('products', ProductsController::class)->parameters(['products' => 'uuid']);
Route::apiResource('file', FilesController::class)->only(['show', 'store'])->parameters([
    'file' => 'uuid'
]);
Route::apiResource('order-status', OrderStatusesController::class)->parameters(['order-status' => 'uuid']);
Route::apiResource('order', OrdersController::class)->parameters(['order' => 'uuid']);
Route::get('/order/{uuid}/download', [OrdersController::class, 'download']);
Route::apiResource('payments', PaymentsController::class)->parameters(['payments' => 'uuid'])->middleware('basic.auth');
