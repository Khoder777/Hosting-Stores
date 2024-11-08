<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthAdminMiddleware;
use App\Http\Controllers\api\Admin\MarketController;
use App\Http\Controllers\api\Admin\AuthController;
use App\Http\Controllers\api\Admin\AdminController;
use App\Http\Controllers\api\Admin\CustomerController;
use App\Http\Controllers\api\Admin\DashboardController;
use App\Http\Controllers\api\Admin\MarketOwnerController;
use App\Http\Controllers\api\Site\CustomerController as SiteCustomerController;

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

Route::group(['prefix' => 'site', 'as' => 'customer.', 'middleware' => 'guest:api-site'], function () {

    Route::view('customer/login', 'customer.auth.login')->name('login');
    Route::post('customer/login', [SiteCustomerController::class, 'login']);
});




Route::group(['prefix' => 'site', 'as' => 'customer.', 'middleware' => 'auth:api-site'], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::post('customer/logout', [AuthController::class, 'logout'])->name('logout');

});