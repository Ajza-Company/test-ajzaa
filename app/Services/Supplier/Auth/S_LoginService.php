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
                'is_registered' => true,
                'is_active' => true,
                'full_mobile' => $data['full_mobile'],
                'password' => $data['password']
            ];

            $user = $this->authenticate($credentials);

            if ($user) {
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
            return response()->json(errorResponse(message: trans(ErrorMessageEnum::LOGIN), error: $exception->getMessage()), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function authenticate(array $credentials): ?User
    {
        return auth()->attempt($credentials) ? auth()->user() : null;
    }
}
