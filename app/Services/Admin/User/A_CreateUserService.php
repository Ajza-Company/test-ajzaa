<?php

namespace App\Services\Admin\User;

use App\Enums\RoleEnum;
use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\v1\User\UserResource;
use App\Events\v1\Frontend\F_UserCreatedEvent;
use App\Repositories\Frontend\User\Create\F_CreateUserInterface;

class A_CreateUserService
{
    /**
     * Create a new instance.
     *
     * @param F_CreateUserInterface $createAccount
     */
    public function __construct(private F_CreateUserInterface $createAccount)
    {

    }

    /**
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    public function create(array $data): JsonResponse
    {
        \DB::beginTransaction();
        try {
            if (!isValidPhone($data['full_mobile'])) {
                return response()->json(errorResponse(message: 'Invalid number detected! Let’s try a different one.'),Response::HTTP_BAD_REQUEST);
            }

            $user = $this->createAccount->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'full_mobile' => $data['full_mobile'],
                'is_registered' => true,
                'gender' => $data['gender'],
                'password' => Hash::make($data['password']),
                'preferred_language' => app()->getLocale()
            ]);

            if (isset($data['avatar'])) {
                $path = uploadFile("user-$user->id", $data['avatar']);
                $user->update(['avatar' => $path]);
            }

            if (!empty($data['permissions'])) {
                try {
                    $user->givePermissionTo($data['permissions']);
                } catch (\Exception $e) {
                    \Log::error('Permission assignment failed: ' . $e->getMessage());
                }
            }        

            $role = Role::where('name', 'Admin')->first();
            $user->syncRoles([$role]);
    
            event(new F_UserCreatedEvent($user, $data['fcm_token'] ?? null));

            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CREATED), data: UserResource::make($user)));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
