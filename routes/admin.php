<?php

use App\Http\Controllers\api\v1\Admin\{A_CompanyController,
    A_NotificationController,
    A_SliderController,
    A_StatisticsController,
    A_StoreController,
    A_SupportChatController,
    A_UserController,
    A_AuthController,
    A_PromoCodeController,
    F_RepOrderController,
    F_RepSalesController,
    A_ProductController,
    F_StateController,
    A_SettingController,
    A_CategoryController,
    A_SubCategoryController,
    A_CarBrandController};
use App\Http\Controllers\api\v1\General\G_TermsController;
use App\Http\Controllers\api\v1\Supplier\S_AuthController;
use App\Http\Controllers\api\v1\Supplier\S_CompanyController;
use App\Http\Controllers\api\v1\Supplier\S_OfferController;
use App\Http\Controllers\api\v1\Supplier\S_OrderController;
use App\Http\Controllers\api\v1\Supplier\S_PermissionController;
use App\Http\Controllers\api\v1\Supplier\S_ProductController;
use App\Http\Controllers\api\v1\Supplier\S_RepOrderController;
use App\Http\Controllers\api\v1\Supplier\S_StatisticsController;
use App\Http\Controllers\api\v1\Supplier\S_StoreController;
use App\Http\Controllers\api\v1\Supplier\S_TeamController;
use App\Http\Controllers\api\v1\Supplier\S_TransactionController;
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

Route::post('terms/{name}/update', [G_TermsController::class, 'updateTerms']);

Route::middleware('guest:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [S_AuthController::class, 'login']);
    });
});

Route::middleware(['auth:sanctum', SetLocale::class])->group(function () {
    Route::get('companies', [A_CompanyController::class, 'index']);
    Route::post('companies', [A_CompanyController::class, 'store']);
    Route::delete('company/{id}/delete', [A_CompanyController::class, 'destroy']);
    Route::get('company/{id}/active', [A_CompanyController::class, 'active']);
    Route::post('stores/{id}/update', [A_StoreController::class, 'update']);
    Route::post('stores/{id}/active', [A_StoreController::class, 'active']);
    Route::post('rep-sales', [F_RepSalesController::class, 'store']);
    Route::post('rep-sales/update/{id}', [F_RepSalesController::class, 'update']);
    Route::post('rep-sales/delete/{id}', [F_RepSalesController::class, 'delete']);
    Route::get('rep-sales', [F_RepSalesController::class, 'index']);
    Route::get('rep-orders/{id?}', [F_RepOrderController::class, 'index']);
    Route::get('rep-chat/{id}', [F_RepOrderController::class, 'repChat']);
    Route::get('users', [A_UserController::class, 'index']);
    Route::post('user/create', [A_UserController::class, 'store']);
    Route::post('user/update/{id}', [A_UserController::class, 'update']);
    Route::post('user/destroy/{id}', [A_UserController::class, 'destroy']);
    Route::get('user/show/{id}', [A_UserController::class, 'show']);
    Route::get('user/admin/permissions', [A_UserController::class, 'getAdminPermission']);
    Route::post('user/block/{id}', [A_UserController::class, 'blockUser']);
    Route::post('user/credit/{id}', [A_UserController::class, 'credit']);
    Route::post('user/debit/{id}', [A_UserController::class, 'debit']);
    Route::post('user/sendNotification/{id}', [A_UserController::class, 'sendNotification']);
    Route::prefix('auth')->group(function () {
        Route::get('virtual-login/{user}', [A_AuthController::class, 'loginWithID']);
    });

    Route::get('product', [A_ProductController::class,'index']);
    Route::post('product', [A_ProductController::class,'store']);
    Route::post('product/{product}', [A_ProductController::class,'update']);
    Route::get('product/show/{product}', [A_ProductController::class,'show']);
    Route::delete('product/delete/{product}', [A_ProductController::class,'destroy']);
    Route::post('activate-product/{product}', [A_ProductController::class,'active']);

    Route::apiResource('promo-codes', A_PromoCodeController::class)->except(['update']);

    //state
    Route::get('state', [F_StateController::class,'index']);
    Route::post('state', [F_StateController::class,'store']);
    Route::post('state/{state}', [F_StateController::class,'update']);
    Route::get('state/show/{state}', [F_StateController::class,'show']);
    Route::delete('state/delete/{state}', [F_StateController::class,'destroy']);

    Route::get('setting', [A_SettingController::class,'index']);
    Route::post('setting/create', [A_SettingController::class,'store']);
    // Support Chat Routes for Admin
    Route::prefix('support')->group(function () {
        Route::get('/chats', [A_SupportChatController::class, 'index']);
        Route::post('/chats/{chat_id}/status', [A_SupportChatController::class, 'updateStatus']);
    });

    Route::prefix('slider')->group(function () {
        Route::get('/', [A_SliderController::class, 'index']);
        Route::post('/', [A_SliderController::class, 'store']);
        Route::delete('{id}', [A_SliderController::class, 'destroy']);
    });

    Route::get('categories', [A_CategoryController::class, 'index']);
    Route::post('category/create', [A_CategoryController::class, 'store']);
    Route::post('category/update/{id}', [A_CategoryController::class, 'update']);
    Route::post('category/destroy/{id}', [A_CategoryController::class, 'destroy']);
    Route::get('category/show/{id}', [A_CategoryController::class, 'show']);
    
    // Category ordering routes
    Route::put('categories/update-order', [A_CategoryController::class, 'updateOrder']);
    Route::get('categories/{parent_id}/subcategories', [A_CategoryController::class, 'getSubCategories']);

    // Car Brands Routes
    Route::get('car-brands', [A_CarBrandController::class, 'index']);
    Route::post('car-brand/create', [A_CarBrandController::class, 'store']);
    Route::post('car-brand/update/{id}', [A_CarBrandController::class, 'update']);
    Route::put('car-brand/update/{id}', [A_CarBrandController::class, 'update']); // Added PUT method
    Route::delete('car-brand/delete/{id}', [A_CarBrandController::class, 'destroy']);
    Route::get('car-brand/show/{id}', [A_CarBrandController::class, 'show']);
    Route::post('car-brand/toggle-active/{id}', [A_CarBrandController::class, 'toggleActive']);

    Route::get('sub-categories/{category_id}', [A_SubCategoryController::class, 'index']);
    Route::post('sub-category/create', [A_SubCategoryController::class, 'store']);
    Route::post('sub-category/update/{id}', [A_SubCategoryController::class, 'update']);
    Route::post('sub-category/destroy/{id}', [A_SubCategoryController::class, 'destroy']);
    Route::get('sub-category/show/{id}', [A_SubCategoryController::class, 'show']);

    Route::get('statistics', [A_StatisticsController::class, 'index']);
    Route::get('orders', [A_StatisticsController::class, 'orders']);

    Route::post('send-notification', A_NotificationController::class);
});
