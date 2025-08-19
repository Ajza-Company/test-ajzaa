<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, string $role = null): Response
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - User not authenticated'
            ], 401);
        }

        // Check if user has any role
        if (!$user->roles || $user->roles->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied - User has no valid role'
            ], 403);
        }

        // Check specific role if provided
        if ($role) {
            $hasRole = false;
            foreach ($user->roles as $userRole) {
                if ($userRole->name === $role) {
                    $hasRole = true;
                    break;
                }
            }
            
            if (!$hasRole) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied - User does not have required role: ' . $role
                ], 403);
            }
        }

        return $next($request);
    }
}
