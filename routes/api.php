<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthAdminMiddleware;
use App\Http\Controllers\api\Admin\AuthController;
use App\Http\Controllers\api\Admin\ShipController;
use App\Http\Controllers\api\Admin\AdminController;
use App\Http\Controllers\api\Admin\MarketController;
use App\Http\Controllers\api\Trader\BrandController;
use App\Http\Controllers\api\Trader\SliderController;
use App\Http\Controllers\api\Admin\CustomerController;
use App\Http\Controllers\api\Admin\DashboardController;
use App\Http\Controllers\api\Trader\CategoryController;
use App\Http\Controllers\api\Admin\MarketOwnerController;
use App\Http\Controllers\api\Trader\SubCategoryController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'dashboard', 'as' => 'admin.', 'middleware' => 'guest:api'], function () {

    Route::view('admin/login', 'admin.auth.login')->name('login');
    Route::post('admin/login', [AuthController::class, 'login']);
});




Route::group(['prefix' => 'dashboard', 'as' => 'admin.', 'middleware' => 'auth:api'], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::post('admin/logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['as' => 'admin.', 'controller' => AdminController::class], function () {
        Route::get('/admin', 'index')->name('index');
        Route::post('/admin/store', 'store')->name('store');
        Route::put('/admin/{id}/update', 'update')->name('update');
    });

    Route::group(['as' => 'slider.', 'controller' => SliderController::class], function () {
        Route::get('/sliders', 'index')->name('index');
        Route::get('/slider/{id}/edit', 'edit')->name('edit');
        Route::post('/slider/store', 'store')->name('store');
        Route::post('/slider/{id}/update', 'update')->name('update');
        Route::delete('/slider/{id}/delete', 'destroy')->name('delete');
    });

    Route::group(['as' => 'market.', 'controller' => MarketController::class], function () {
        Route::get('/market', 'index')->name('index');
        Route::get('/market/{id}/edit', 'edit')->name('edit');
        Route::post('/market/store', 'store')->name('store');
        Route::post('/market/{id}/update', 'update')->name('update');
        Route::delete('/market/{id}/delete', 'destroy')->name('delete');
        Route::put('/market_change_status/{id}', 'changeStatus')->name('changeStatus');
    });

    Route::group(['as' => 'marketOwner.', 'controller' => MarketOwnerController::class], function () {
        Route::get('/marketOwner', 'index')->name('index');
        Route::post('/marketOwner/store', 'store')->name('store');
        Route::post('/marketOwner/{id}/update', 'update')->name('update');
        Route::post('/marketOwner/{id}/block', 'block')->name('block');
        Route::post('/marketOwner/{id}/unblock', 'unblock')->name('unblock');
    });

    Route::group(['as' => 'customer.', 'controller' => CustomerController::class], function () {
        Route::get('/customer', 'index')->name('index');
        Route::put('/customer_change_status/{id}', 'changeStatus')->name('changeStatus');
    });

    Route::group(['as' => 'ship.', 'controller' => ShipController::class], function () {
        Route::get('/ship', 'index')->name('index');
        Route::post('/ship/store', 'store')->name('store');
        Route::put('/ship/{id}/update', 'update')->name('update');
        Route::put('/ship_change_status/{id}', 'changeStatus')->name('changeStatus');
        Route::delete('/ship/{id}/delete', 'destroy')->name('delete');
    });
});


Route::group(['prefix' => 'trader', 'as' => 'admin.', 'middleware' => 'auth:api'], function () {
    Route::group(['as' => 'category.', 'controller' => CategoryController::class], function () {
        Route::get('/category', 'index')->name('index');
        Route::post('/category/store', 'store')->name('store');
        Route::post('/category/{id}/update', 'update')->name('update');
        Route::get('/category/{id}/edit', 'edit')->name('edit');
        Route::delete('/category/{id}/delete', 'destroy')->name('delete');
    });

    Route::group(['as' => 'subCategory.', 'controller' => SubCategoryController::class], function () {
        Route::get('/subCategory', 'index')->name('index');
        Route::post('/subCategory/store', 'store')->name('store');
        Route::post('/subCategory/{id}/update', 'update')->name('update');
        Route::get('/subCategory/{id}/edit', 'edit')->name('edit');
        Route::delete('/subCategory/{id}/delete', 'destroy')->name('delete');
    });

    Route::group(['as' => 'brand.', 'controller' => BrandController::class], function () {
        Route::get('/brand', 'index')->name('index');
        Route::get('/brand/{id}/edit', 'edit')->name('edit');
        Route::post('/brand/store', 'store')->name('store');
        Route::post('/brand/{id}/update', 'update')->name('update');
        Route::delete('/brand/{id}/delete', 'destroy')->name('delete');
    });
});
require __DIR__ . '/apiSite.php';