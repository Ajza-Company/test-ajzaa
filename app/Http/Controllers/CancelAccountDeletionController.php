<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CancelAccountDeletionController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Check if user is a client (has Client role or no role)
            $hasClientRole = $user->hasRole('Client');
            $hasNoRole = $user->roles()->count() === 0;
            
            if (!$hasClientRole && !$hasNoRole) {
                return response()->json([
                    'success' => false,
                    'message' => 'Account deletion cancellation is only available for clients'
                ], 403);
            }

            if (!$user->isPendingDeletion()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('general.no_deletion_request_found')
                ], 400);
            }

            DB::beginTransaction();
            
            $user->update([
                'deletion_status' => 'active',
                'deletion_requested_at' => null,
                'deletion_reason' => null
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => trans('general.deletion_cancelled_successfully')
            ]);

        } catch (Throwable $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request'
            ], 500);
        }
    }
}
