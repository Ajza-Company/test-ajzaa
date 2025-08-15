<?php

use App\Http\Controllers\api\v1\Frontend\F_AddressController;
use App\Http\Controllers\api\v1\Frontend\F_AjzaOfferController;
use App\Http\Controllers\api\v1\Frontend\F_AuthController;
use App\Http\Controllers\api\v1\Frontend\F_CartController;
use App\Http\Controllers\api\v1\Frontend\F_CarBrandController;
use App\Http\Controllers\api\v1\Frontend\F_CarModelController;
use App\Http\Controllers\api\v1\Frontend\F_CarTypeController;
use App\Http\Controllers\api\v1\Frontend\F_CategoryController;
use App\Http\Controllers\api\v1\Frontend\F_FavoriteController;
use App\Http\Controllers\api\v1\Frontend\F_HomeController;
use App\Http\Controllers\api\v1\Frontend\F_LocaleController;
use App\Http\Controllers\api\v1\Frontend\F_OrderController;
use App\Http\Controllers\api\v1\Frontend\F_PayController;
use App\Http\Controllers\api\v1\Frontend\F_ProductController;
use App\Http\Controllers\api\v1\Frontend\F_RepOrderController;
use App\Http\Controllers\api\v1\Frontend\F_RepReviewController;
use App\Http\Controllers\api\v1\Frontend\F_SliderImageController;
use App\Http\Controllers\api\v1\Frontend\F_StoreController;
use App\Http\Controllers\api\v1\Frontend\F_StoreReviewController;
use App\Http\Controllers\api\v1\Frontend\F_WalletController;
use App\Http\Controllers\api\v1\General\G_AreaController;
use App\Http\Controllers\api\v1\General\G_StateController;
use App\Http\Middleware\SetLocale;
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

Route::group([], function () {
    Route::get('locales', F_LocaleController::class);
    Route::prefix('auth')->group(function () {
        Route::post('send-otp', [F_AuthController::class, 'sendOtp']);
        Route::post('verify-otp', [F_AuthController::class, 'verifyOtp']);
        Route::post('create-account', [F_AuthController::class, 'createAccount']);
        Route::post('setup-account', [F_AuthController::class, 'setupAccount']);
    });

    Route::middleware(SetLocale::class)->group(function () {
        Route::get('ajza-offers', [F_ProductController::class, '__invoke']);

        Route::prefix('car-brands')->group(function () {
            Route::get('/', F_CarBrandController::class);
            Route::get('{car_brand}/car-models', F_CarModelController::class);
        });

        Route::get('car-types', F_CarTypeController::class);
        Route::get('slider-images', F_SliderImageController::class);

        Route::prefix('stores')->group(function () {
            Route::controller(F_StoreController::class)->group(function () {
                Route::get('/', '__invoke');
                Route::get('{store_id}/details', 'show');
            });

            Route::get('products', [F_ProductController::class, '__invoke']);
            Route::get('{store_id}/products', [F_ProductController::class, '__invoke']);
            Route::get('products/{product_id}', [F_ProductController::class, 'show']);
        });
    });
});

Route::middleware(['auth:sanctum', SetLocale::class])->group(function () {

    Route::get('home', F_HomeController::class);

    Route::prefix('favorites')->group(function () {
        Route::get('/', [F_FavoriteController::class, 'index']);
        Route::post('/', [F_FavoriteController::class, 'store']);
        Route::delete('{product_id?}', [F_FavoriteController::class, 'destroy']);
    });

    Route::prefix('addresses')->group(function () {
        Route::get('/', [F_AddressController::class, 'index']);
        Route::post('/', [F_AddressController::class, 'store']);
        Route::post('{id}', [F_AddressController::class, 'update']);
        Route::delete('{id}', [F_AddressController::class, 'destroy']);
    });

    Route::prefix('auth')->group(function () {
        Route::post('update-profile', [F_AuthController::class, 'updateProfile']);
        Route::get('me', [F_AuthController::class, 'me']);
        Route::get('company-virtual-login/{store}', [F_AuthController::class, 'loginCompanyWithID']);
    });

    Route::prefix('user')->group(function () {
        Route::post('setup-account', [F_AuthController::class, 'setupAccount']);
        Route::get('wallet-transactions', F_WalletController::class);
    });

    Route::post('stores/{store_id}/orders/create', [F_OrderController::class, 'store']);
    Route::post('stores/{store_id}/orders/getInvoice', [F_OrderController::class, 'getInvoice']);
    Route::post('stores/cart', [F_CartController::class, 'show']);

    Route::prefix('orders')->group(function () {
        Route::get('/', [F_OrderController::class, 'index']);
        Route::get('{order_id}/show', [F_OrderController::class, 'show']);
        Route::post('{order_id}/success', [F_OrderController::class, 'successPay']);
        Route::post('{order_id}/cancel', [F_OrderController::class, 'cancel']);
        Route::post('{order_id}/submit-review', F_StoreReviewController::class);
        Route::post('{order_id}/pay', F_PayController::class);
    });

    Route::prefix('rep-orders')->group(function () {
        Route::get('/', [F_RepOrderController::class, 'orders']);
        Route::post('create', [F_RepOrderController::class, 'createOrder']);
        Route::get('{order_id}/check-order-acceptance', [F_RepOrderController::class, 'checkIfAccepted']);
        Route::get('{order_id}/delivered', [F_RepOrderController::class, 'orderDelivered']);
        Route::get('{order_id}/cancel', [F_RepOrderController::class, 'cancelOrder']);
        Route::post('{order_id}/submit-review', F_RepReviewController::class);
    });
});
