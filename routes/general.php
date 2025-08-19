<?php

use App\Http\Controllers\api\v1\Frontend\F_CategoryController;
use App\Http\Controllers\api\v1\Frontend\F_LogoutController;
use App\Http\Controllers\api\v1\Frontend\F_RepOrderController;
use App\Http\Controllers\api\v1\General\{
    G_AreaController,
    G_CancelOrderController,
    G_NotificationController,
    G_RepChatController,
    G_SupportChatController,
    G_StateController,
    G_CountryController,
    G_ProductController,
    G_StatisticsController,
    G_TermsController};
use App\Http\Controllers\DeleteAccountController;
use App\Http\Controllers\CancelAccountDeletionController;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\CheckAccountDeletionStatus;
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
    Route::get('terms/{name}', [G_TermsController::class, 'terms']);
    Route::middleware(SetLocale::class)->group(function () {
        Route::get('delete-account', DeleteAccountController::class);
        Route::get('categories', F_CategoryController::class);
        Route::get('countries', G_CountryController::class);
        Route::prefix('cities')->group(function () {
            Route::get('/', G_StateController::class);
            Route::get('{city_id}/areas', G_AreaController::class);
        });
    });
});

Route::prefix('v1')->group(function () {
    Route::middleware(SetLocale::class)->group(function () {
        Route::get('notifications', G_NotificationController::class);
    });
    
    // Settings routes
    Route::get('settings/{name}', [G_TermsController::class, 'terms']);
    Route::put('settings/{name}', [G_TermsController::class, 'updateTerms'])->middleware(['auth:sanctum', 'role:Admin']);
    
    Route::prefix('orders')->group(function () {
        Route::post('{order_id}/cancel', G_CancelOrderController::class);
    });

    Route::get('/products', G_ProductController::class);

    Route::prefix('rep-orders')->group(function () {
        Route::get('/chats', [G_RepChatController::class, 'index']);
        Route::get('/chats/{chat_id}', [G_RepChatController::class, 'show']);
        Route::get('/chats/{chat_id}/messages', [G_RepChatController::class, 'messages']);
        Route::post('/chats/{chat_id}/messages', [G_RepChatController::class, 'sendMessage']);
        Route::post('/chats/{chat_id}/offers', [G_RepChatController::class, 'sendOffer']);

        // Offer routes
        Route::post('/offers/{offer}/update', [G_RepChatController::class, 'updateOffer']);
        Route::get('/invoices/{invoice}/view', [F_RepOrderController::class, 'viewInvoice']);
    });

    Route::get('Statistics',G_StatisticsController::class);

    // Support Chat Routes
    Route::prefix('support')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/chats', [G_SupportChatController::class, 'index']);
        Route::post('/chats', [G_SupportChatController::class, 'store']);
        Route::get('/chats/{chat_id}', [G_SupportChatController::class, 'show']);
        Route::get('/chats/{chat_id}/messages', [G_SupportChatController::class, 'messages']);
        Route::post('/chats/{chat_id}/messages', [G_SupportChatController::class, 'sendMessage']);
        Route::get('/chats/{chat_id}/close', [G_SupportChatController::class, 'close']);
        Route::get('/chats/{chat_id}/reopen', [G_SupportChatController::class, 'reopen']);
    });
});

// Account deletion routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('delete-account', DeleteAccountController::class)->name('delete-account');
    Route::post('cancel-deletion', CancelAccountDeletionController::class)->name('cancel-deletion');
    Route::post('logout', F_LogoutController::class)->name('logout');
});

// Protected routes that should check deletion status
Route::middleware(['auth:sanctum', CheckAccountDeletionStatus::class])->group(function () {
    // Note: categories moved to public routes above for guest access
    Route::get('countries', G_CountryController::class);
    Route::prefix('cities')->group(function () {
        Route::get('/', G_StateController::class);
        Route::get('{city_id}/areas', G_AreaController::class);
    });
    Route::get('v1/products', G_ProductController::class);
    Route::get('v1/notifications', G_NotificationController::class);
});
