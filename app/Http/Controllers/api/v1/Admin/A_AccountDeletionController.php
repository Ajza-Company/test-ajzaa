<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class A_AccountDeletionController extends Controller
{
    /**
     * Display a listing of users with pending deletion (clients only)
     */
    public function index(Request $request)
    {
        try {
            $query = User::query()
                ->where('deletion_status', 'pending_deletion')
                ->where(function($q) {
                    // Only show clients (users with Client role or no role)
                    $q->whereHas('roles', function($roleQuery) {
                        $roleQuery->where('name', 'Client');
                    })->orWhereDoesntHave('roles');
                });

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $users = $query->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $users
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch deletion requests'
            ], 500);
        }
    }

    /**
     * Display a listing of deleted users (clients only)
     */
    public function deletedUsers(Request $request)
    {
        try {
            $query = User::query()
                ->where('deletion_status', 'deleted')
                ->where(function($q) {
                    // Only show clients (users with Client role or no role)
                    $q->whereHas('roles', function($roleQuery) {
                        $roleQuery->where('name', 'Client');
                    })->orWhereDoesntHave('roles');
                });

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $users = $query->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $users
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch deleted users'
            ], 500);
        }
    }
    
    /**
     * Reactivate a deleted user (clients only)
     */
    public function reactivate(Request $request, $userId)
    {
        try {
            $user = User::find($userId);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Check if user is a client (has Client role or no role)
            $hasClientRole = $user->hasRole('Client');
            $hasNoRole = $user->roles()->count() === 0;
            
            if (!$hasClientRole && !$hasNoRole) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only client accounts can be reactivated'
                ], 403);
            }

            if ($user->deletion_status !== 'deleted') {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not deleted'
                ], 400);
            }

            $user->update([
                'deletion_status' => 'active',
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User reactivated successfully'
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reactivate user'
            ], 500);
        }
    }
    
    /**
     * Get deletion statistics (clients only)
     */
    public function statistics()
    {
        try {
            $pendingDeletions = User::where('deletion_status', 'pending_deletion')
                ->where(function($q) {
                    // Only count clients (users with Client role or no role)
                    $q->whereHas('roles', function($roleQuery) {
                        $roleQuery->where('name', 'Client');
                    })->orWhereDoesntHave('roles');
                })->count();

            $deletedAccounts = User::where('deletion_status', 'deleted')
                ->where(function($q) {
                    // Only count clients (users with Client role or no role)
                    $q->whereHas('roles', function($roleQuery) {
                        $roleQuery->where('name', 'Client');
                    })->orWhereDoesntHave('roles');
                })->count();

            $activeAccounts = User::where('deletion_status', 'active')
                ->where(function($q) {
                    // Only count clients (users with Client role or no role)
                    $q->whereHas('roles', function($roleQuery) {
                        $roleQuery->where('name', 'Client');
                    })->orWhereDoesntHave('roles');
                })->count();

            $totalClients = User::where(function($q) {
                // Count all clients (users with Client role or no role)
                $q->whereHas('roles', function($roleQuery) {
                    $roleQuery->where('name', 'Client');
                })->orWhereDoesntHave('roles');
            })->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'pending_deletions' => $pendingDeletions,
                    'deleted_accounts' => $deletedAccounts,
                    'active_accounts' => $activeAccounts,
                    'total_clients' => $totalClients
                ]
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics'
            ], 500);
        }
    }
}
