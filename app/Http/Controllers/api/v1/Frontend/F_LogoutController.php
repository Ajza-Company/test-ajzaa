<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Http\Controllers\Controller;
use App\Enums\SuccessMessagesEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class F_LogoutController extends Controller
{
    /**
     * Handle user logout
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            
            if ($user) {
                // Revoke all user tokens (Laravel Sanctum)
                \Laravel\Sanctum\PersonalAccessToken::where('tokenable_id', $user->id)
                    ->where('tokenable_type', get_class($user))
                    ->delete();
                
                // Clear FCM token if exists
                if ($request->has('fcm_token')) {
                    \App\Models\UserFcmToken::where('user_id', $user->id)
                        ->where('token', $request->fcm_token)
                        ->delete();
                }
                
                return response()->json([
                    'success' => true,
                    'message' => trans(SuccessMessagesEnum::LOGGEDOUT ?? 'Logged out successfully'),
                    'data' => null
                ]);
            }
            
            // If no user is authenticated, still return success (user is already logged out)
            return response()->json([
                'success' => true,
                'message' => 'Already logged out',
                'data' => null
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
