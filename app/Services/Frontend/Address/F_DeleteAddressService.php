<?php

namespace App\Services\Frontend\Address;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class F_DeleteAddressService
{
    /**
     *
     * @param User $user
     * @param string $id
     * @return JsonResponse
     */
    public function delete(User $user, string $id): JsonResponse
    {
        try {
            $address = $user->addresses->where('id', decodeString($id))->first();

            if (!$address) {
                return response()->json(errorResponse(
                    message: ErrorMessageEnum::UPDATE,
                    error: 'Address not found'),
                    Response::HTTP_NOT_FOUND);
            }

            $address->delete();

            return response()->json(successResponse(message: SuccessMessagesEnum::DELETED));
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: ErrorMessageEnum::DELETE,
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
