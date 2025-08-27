<?php

use App\Http\Controllers\api\v1\Frontend\F_PaymentCallbackController;
use App\Http\Controllers\api\v1\Frontend\F_CarBrandController;
use App\Http\Controllers\api\v1\Frontend\F_CarModelController;
use App\Http\Controllers\api\v1\Frontend\F_CarTypeController;
use App\Http\Controllers\api\v1\Frontend\F_CategoryController;
use App\Http\Controllers\api\v1\Frontend\F_ProductController;
use App\Http\Controllers\api\v1\Frontend\F_StoreController;
use App\Http\Controllers\api\v1\Frontend\InterPayController;
use App\Http\Controllers\api\v1\General\G_CountryController;
use App\Http\Controllers\api\v1\General\G_StateController;
use App\Http\Controllers\api\v1\General\G_AreaController;
use Illuminate\Support\Facades\Route;

Route::post('payment/callback', F_PaymentCallbackController::class)->name('payment.callback');

// InterPay Routes
Route::prefix('interpay')->group(function () {
    Route::post('/generate-token', [App\Http\Controllers\api\v1\InterPay\InterPayController::class, 'generateToken']);
    Route::post('/callback', [App\Http\Controllers\api\v1\InterPay\InterPayController::class, 'callback']);
    Route::post('/generate-tokens', [App\Http\Controllers\api\v1\Frontend\InterPayController::class, 'generateTokens']);
});

// Test Routes
Route::prefix('test')->group(function () {
    Route::post('/broadcast', [App\Http\Controllers\api\v1\Test\TestBroadcastController::class, 'testBroadcast']);
    Route::post('/private-channel', [App\Http\Controllers\api\v1\Test\TestBroadcastController::class, 'testPrivateChannel']);
});

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
        Route::get('categories', [App\Http\Controllers\api\v1\General\CategoryController::class, 'index']);
    });

    // Custom Categories routes
    Route::prefix('companies')->group(function () {
        Route::get('{company}/custom-categories', [App\Http\Controllers\api\v1\CustomCategoryController::class, 'index']);
        Route::post('{company}/custom-categories', [App\Http\Controllers\api\v1\CustomCategoryController::class, 'store']);
        Route::put('custom-categories/{category}', [App\Http\Controllers\api\v1\CustomCategoryController::class, 'update']);
        Route::delete('custom-categories/{category}', [App\Http\Controllers\api\v1\CustomCategoryController::class, 'destroy']);
        
        // Company Products routes
        Route::prefix('{company}/products')->group(function () {
            Route::get('/', [App\Http\Controllers\api\v1\CompanyProductController::class, 'index']);
            Route::get('count', [App\Http\Controllers\api\v1\CompanyProductController::class, 'getProductsCount']);
            Route::get('search', [App\Http\Controllers\api\v1\CompanyProductController::class, 'searchProducts']);
            Route::get('statistics', [App\Http\Controllers\api\v1\CompanyProductController::class, 'getStatistics']);
        });
        Route::get('{company}/categories/{category}/products', [App\Http\Controllers\api\v1\CompanyProductController::class, 'getByCategory']);
        
        // Bulk Operations routes
        Route::prefix('{company}/custom-categories')->group(function () {
            Route::post('bulk/order', [App\Http\Controllers\api\v1\CustomCategoryBulkController::class, 'updateOrder']);
            Route::post('bulk/status', [App\Http\Controllers\api\v1\CustomCategoryBulkController::class, 'updateStatus']);
            Route::get('statistics', [App\Http\Controllers\api\v1\CustomCategoryBulkController::class, 'statistics']);
            Route::get('search', [App\Http\Controllers\api\v1\CustomCategoryBulkController::class, 'search']);
        });
    });
    
    // User Permissions routes
    Route::prefix('permissions')->group(function () {
        Route::get('my-permissions', [App\Http\Controllers\UserPermissionsController::class, 'checkMyPermissions']);
        Route::get('check-user', [App\Http\Controllers\UserPermissionsController::class, 'checkUserPermissionsByMobile']);
        Route::post('check-permission', [App\Http\Controllers\UserPermissionsController::class, 'checkPermission']);
        Route::post('check-role', [App\Http\Controllers\UserPermissionsController::class, 'checkRole']);
        Route::get('all', [App\Http\Controllers\UserPermissionsController::class, 'getAllPermissions']);
        Route::get('roles', [App\Http\Controllers\UserPermissionsController::class, 'getAllRoles']);
    });
});
