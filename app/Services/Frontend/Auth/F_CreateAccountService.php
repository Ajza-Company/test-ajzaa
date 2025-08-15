<?php

namespace App\Services\Frontend\Auth;

use App\Enums\ErrorMessageEnum;
use App\Enums\RoleEnum;
use App\Enums\SuccessMessagesEnum;
use App\Events\v1\Frontend\F_UserCreatedEvent;
use App\Http\Resources\v1\User\UserResource;
use App\Repositories\Frontend\User\Create\F_CreateUserInterface;
use App\Repositories\Frontend\Wallet\Create\F_CreateWalletInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class F_CreateAccountService
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
                'gender' => isset($data['personal']) ? $data['personal']['gender'] : null,
                'password' => '12345678',
                'preferred_language' => app()->getLocale()
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;

            if(isset($data['personal'])) {
                $user->assignRole(RoleEnum::CLIENT);
            }

            if(isset($data['workshop'])) {
                $user->assignRole(RoleEnum::WORKSHOP);
            }

            event(new F_UserCreatedEvent($user, $data['fcm_token'] ?? null));

            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CREATED), data: UserResource::make($user), token: $token));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
