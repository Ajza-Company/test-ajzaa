<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountDeletionStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Always log to see if middleware is called
        Log::info('CheckAccountDeletionStatus Middleware Called', [
            'path' => $request->path(),
            'method' => $request->method(),
            'auth_check' => Auth::check()
        ]);
        
        if (Auth::check()) {
            $user = Auth::user();
            
            // Debug logging
            Log::info('Middleware Check', [
                'user_id' => $user->id,
                'email' => $user->email,
                'deletion_status' => $user->deletion_status,
                'is_pending' => $user->isPendingDeletion(),
                'current_path' => $request->path()
            ]);
            
            // Block actions for deleted users
            if ($user->isDeleted()) {
                Log::info('Blocking deleted user', ['user_id' => $user->id]);
                return response()->json([
                    'success' => false,
                    'message' => trans('general.account_deleted_cannot_access')
                ], 403);
            }
            
            // Block actions for pending deletion users (except specific routes)
            if ($user->isPendingDeletion()) {
                $allowedPaths = [
                    'api/general/delete-account',
                    'api/general/cancel-deletion',
                    'api/general/logout'
                ];
                
                $currentPath = $request->path();
                if (!in_array($currentPath, $allowedPaths)) {
                    Log::info('Blocking pending deletion user', [
                        'user_id' => $user->id,
                        'current_path' => $currentPath,
                        'allowed_paths' => $allowedPaths
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => trans('general.account_pending_deletion')
                    ], 403);
                }
            }
            
            // Block orders and dashboard access for inactive users
            if ($user->isBlocked()) {
                $blockedPaths = [
                    'orders',
                    'dashboard',
                    'profile',
                    'favorites'
                ];
                
                $currentPath = $request->path();
                foreach ($blockedPaths as $pattern) {
                    if (str_contains($currentPath, $pattern)) {
                        Log::info('Blocking blocked user', [
                            'user_id' => $user->id,
                            'current_path' => $currentPath,
                            'pattern' => $pattern
                        ]);
                        return response()->json([
                            'success' => false,
                            'message' => trans('general.account_inactive_cannot_access')
                        ], 403);
                    }
                }
            }
        }
        
        return $next($request);
    }
}
