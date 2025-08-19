<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, string $permission = null): Response
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - User not authenticated'
            ], 401);
        }

        // Check if user has any role by checking the roles relationship
        if (!$user->roles || $user->roles->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied - User has no valid role'
            ], 403);
        }

        // Check specific permission if provided
        if ($permission) {
            $hasPermission = false;
            foreach ($user->roles as $role) {
                if ($role->permissions->where('name', $permission)->count() > 0) {
                    $hasPermission = true;
                    break;
                }
            }
            
            if (!$hasPermission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied - Insufficient permissions'
                ], 403);
            }
        }

        return $next($request);
    }
}
