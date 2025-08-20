<?php

namespace App\Services\Supplier\Auth;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Http\Resources\v1\User\UserResource;
use App\Models\User;
use App\Repositories\General\FcmToken\Create\G_CreateFcmTokenInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class S_LoginService
{

    /**
     * Create the event listener.
     */
    public function __construct(private G_CreateFcmTokenInterface $createFcmToken)
    {
        //
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public function login(array $data): JsonResponse
    {
        try {
            $credentials = [
                'full_mobile' => $data['full_mobile'],
                'password' => $data['password']
            ];

            // Debug: Log the credentials (remove in production)
            Log::info('Login attempt', ['mobile' => $data['full_mobile'], 'credentials' => $credentials]);

            $user = $this->authenticate($credentials);

            // Debug: Log the result
            Log::info('Authentication result', ['user' => $user ? $user->id : 'null']);

            if ($user) {
                // Create FCM token if provided
                if (isset($data['fcm_token'])) {
                    $this->createFcmToken->create([
                        'user_id' => $user->id,
                        'token' => $data['fcm_token']
                    ]);
                }

                return response()->json(successResponse(
                    message: trans(SuccessMessagesEnum::LOGGEDIN),
                    data: UserResource::make($user->load('stores', 'roles','company')),
                    token: $user->createToken('auth_token')->plainTextToken
                ));
            }

            return response()->json(errorResponse(message: 'Invalid mobile number and/or password'), Response::HTTP_BAD_REQUEST);
        } catch (\Exception $exception) {
            Log::error('Login error', ['error' => $exception->getMessage()]);
            return response()->json(errorResponse(message: trans(ErrorMessageEnum::LOGIN), error: $exception->getMessage()), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function authenticate(array $credentials): ?User
    {
        // Find user by mobile and verify password manually
        $user = User::where('full_mobile', $credentials['full_mobile'])->first();
        
        if ($user && Hash::check($credentials['password'], $user->password)) {
            return $user;
        }
        
        return null;
    }

    /**
     * Auto assign permissions if user doesn't have any
     */
    private function autoAssignPermissionsIfNeeded($user): void
    {
        // Skip if user already has permissions
        if ($user->permissions && $user->permissions->count() > 0) {
            return;
        }

        // Skip if user has no roles
        if (!$user->roles || $user->roles->isEmpty()) {
            return;
        }

        // Get user's first role
        $userRole = $user->roles->first();
        
        // Skip if user is Client
        if ($userRole->name === 'Client') {
            return;
        }

        // Auto assign permissions based on role
        $this->assignRolePermissions($user, $userRole);
    }

    /**
     * Assign permissions based on role
     */
    private function assignRolePermissions($user, $role): void
    {
        $permissions = [];

        switch ($role->name) {
            case 'Admin':
                $permissions = $this->getAdminPermissions();
                break;
            case 'Supplier':
                $permissions = $this->getSupplierPermissions();
                break;
            case 'Workshop':
                $permissions = $this->getWorkshopPermissions();
                break;
            case 'Representative':
                $permissions = $this->getRepresentativePermissions();
                break;
        }

        // Assign permissions to user
        if (!empty($permissions)) {
            foreach ($permissions as $permissionName) {
                $permission = \Spatie\Permission\Models\Permission::where('name', $permissionName)->first();
                if ($permission && !$user->hasPermissionTo($permissionName)) {
                    $user->givePermissionTo($permission);
                }
            }
        }
    }

    /**
     * Get Admin permissions
     */
    private function getAdminPermissions(): array
    {
        return [
            'a.show-all-users',
            'a.control-user',
            'a.show-all-stores',
            'a.control-store',
            'a.show-all-repSales',
            'a.control-repSales',
            'a.show-all-promos',
            'a.control-promo',
            'a.show-all-products',
            'a.control-product',
            'a.show-all-states',
            'a.control-state',
            'a.show-all-offers',
            'a.control-offers',
            'a.show-all-chat',
            'a.control-chat',
            // Also give supplier permissions
            'show-all-permissions',
            'view-orders',
            'accept-orders',
            'view-offers',
            'control-offer',
            'view-users',
            'edit-users',
            'view-stores',
            'edit-stores',
            'view-categories',
            'edit-categories',
            'view-products',
            'edit-products',
            'view-orders-statistics'
        ];
    }

    /**
     * Get Supplier permissions
     */
    private function getSupplierPermissions(): array
    {
        return [
            'show-all-permissions',
            'view-orders',
            'accept-orders',
            'view-offers',
            'control-offer',
            'view-users',
            'edit-users',
            'view-stores',
            'edit-stores',
            'view-categories',
            'edit-categories',
            'view-products',
            'edit-products',
            'view-orders-statistics'
        ];
    }

    /**
     * Get Workshop permissions
     */
    private function getWorkshopPermissions(): array
    {
        return [
            'view-orders',
            'accept-orders',
            'view-offers',
            'view-stores',
            'view-products',
            'view-categories'
        ];
    }

    /**
     * Get Representative permissions
     */
    private function getRepresentativePermissions(): array
    {
        return [
            'view-orders',
            'accept-orders',
            'view-offers',
            'view-stores',
            'view-products',
            'view-categories'
        ];
    }
}
