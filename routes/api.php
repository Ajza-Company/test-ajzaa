<?php

use App\Http\Controllers\api\v1\Frontend\F_PaymentCallbackController;
use App\Http\Controllers\api\v1\Frontend\F_CarBrandController;
use App\Http\Controllers\api\v1\Frontend\F_CarModelController;
use App\Http\Controllers\api\v1\Frontend\F_CarTypeController;
use App\Http\Controllers\api\v1\Frontend\F_CategoryController;
use App\Http\Controllers\api\v1\Frontend\F_ProductController;
use App\Http\Controllers\api\v1\Frontend\F_StoreController;
use App\Http\Controllers\api\v1\General\G_CountryController;
use App\Http\Controllers\api\v1\General\G_StateController;
use App\Http\Controllers\api\v1\General\G_AreaController;
use Illuminate\Support\Facades\Route;

Route::post('payment/callback', F_PaymentCallbackController::class)->name('payment.callback');

// API v1 routes
Route::prefix('v1')->group(function () {
    // Frontend routes
    Route::prefix('frontend')->group(function () {
        Route::get('car-brands', F_CarBrandController::class);
        Route::get('car-brands/{car_brand}/car-models', F_CarModelController::class);
        Route::get('car-types', F_CarTypeController::class);
        Route::get('categories', F_CategoryController::class);
        Route::get('products', [F_ProductController::class, '__invoke']);
        Route::get('stores', [F_StoreController::class, '__invoke']);
    });
    
    // General routes
    Route::prefix('general')->group(function () {
        Route::get('countries', G_CountryController::class);
        Route::get('cities', G_StateController::class);
        Route::get('cities/{city_id}/areas', G_AreaController::class);
    });
});
