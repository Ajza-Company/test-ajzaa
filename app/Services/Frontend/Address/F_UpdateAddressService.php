<?php

namespace App\Services\Frontend\Address;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class F_UpdateAddressService
{
    /**
     *
     * @param User $user
     * @param array $data
     * @param string $id
     * @return JsonResponse
     */
    public function update(User $user, array $data, string $id): JsonResponse
    {
        try {
            if (isset($data['is_default']) && $data['is_default']) {
                $user->addresses()->update(['is_default' => false]);
            }

            $address = $user->addresses->where('id', decodeString($id))->first();

            if (!$address) {
                return response()->json(errorResponse(
                    message: ErrorMessageEnum::UPDATE,
                    error: 'Address not found'),
                    Response::HTTP_NOT_FOUND);
            }

            $address->update($data);

            return response()->json(successResponse(message: SuccessMessagesEnum::UPDATED));
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: ErrorMessageEnum::UPDATE,
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
