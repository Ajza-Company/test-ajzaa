<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCustomCategoryPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        // System admin has full access
        if ($user->hasPermission('manage_categories')) {
            return $next($request);
        }

        // Check if user is company admin
        $companyId = $request->route('company');
        
        if ($companyId && $user->hasPermission('manage_company_categories', $companyId)) {
            return $next($request);
        }

        // Check if user is updating/deleting their own company's category
        if ($request->route('category')) {
            $category = \App\Models\Category::find($request->route('category'));
            
            if ($category && $category->company_id && 
                $user->hasPermission('manage_company_categories', $category->company_id)) {
                return $next($request);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Insufficient permissions'
        ], 403);
    }
}
