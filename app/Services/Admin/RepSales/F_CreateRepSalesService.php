<?php

namespace App\Services\Admin\RepSales;

use App\Enums\ErrorMessageEnum;
use App\Enums\RoleEnum;
use App\Enums\SuccessMessagesEnum;
use App\Events\v1\Frontend\F_UserCreatedEvent;
use App\Http\Resources\v1\User\UserResource;
use App\Repositories\Frontend\User\Create\F_CreateUserInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class F_CreateRepSalesService
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
                'password' => $data['password'],
                'state_id' => $data['city_id'],
                'preferred_language' => app()->getLocale()
            ]);

            if (isset($data['avatar'])) {
                $path = uploadFile("user-$user->id", $data['avatar']);
                $user->update(['avatar' => $path]);
            }

            $user->assignRole(RoleEnum::REPRESENTATIVE);



            event(new F_UserCreatedEvent($user, $data['fcm_token'] ?? null));

            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CREATED), data: UserResource::make($user->load('state'))));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
