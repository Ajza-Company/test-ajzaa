<?php

namespace App\Services\Frontend\Auth;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Models\Personal;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class F_SetupAccountService
{
    /**
     *
     * @param User $user
     * @param array $data
     *
     * @return JsonResponse
     */
    public function setup(User $user, array $data): JsonResponse
    {
        try {
            Personal::updateOrCreate(['user_id' => $user->id], $data['personal']);
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::UPDATED)));
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::UPDATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
