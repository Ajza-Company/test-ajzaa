<?php

namespace App\Http\Controllers\api\v1\Admin;

use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\User\UserResource;
use App\Repositories\Supplier\User\Find\S_FindUserInterface;

class A_AuthController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param S_FindUserInterface $findUser
     */
    public function __construct(
        private S_FindUserInterface $findUser)
    {

    }


    /**
     * Login with id Function
     *
     * @return JsonResponse
     */
    public function loginWithID(string $user_id)
    {
        $user = $this->findUser->find(decodeString($user_id));
        
        return response()->json(successResponse(
            message: trans(SuccessMessagesEnum::LOGGEDIN),
            data: UserResource::make($user->load('stores', 'roles')),
            token: $user->createToken('virtual_auth_token')->plainTextToken
        ));

    }

}
