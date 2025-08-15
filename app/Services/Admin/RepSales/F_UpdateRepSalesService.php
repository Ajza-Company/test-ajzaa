<?php

namespace App\Services\Admin\RepSales;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Http\Resources\v1\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class F_UpdateRepSalesService
{
    /**
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    public function update(array $data,$user): JsonResponse
    {
        \DB::beginTransaction();
        try {
            if (!isValidPhone($data['full_mobile'])) {
                return response()->json(errorResponse(message: 'Invalid number detected! Let’s try a different one.'),Response::HTTP_BAD_REQUEST);
            }

            $updateArray = [
                'name' => $data['name'],
                'email' => $data['email']
            ];

            if (isset($data['full_mobile'])) {
                $updateArray['full_mobile'] = $data['full_mobile'];
            }

            if (isset($data['password'])) {
                $updateArray['password'] = $data['password'];
            }

            if (isset($data['gender'])) {
                $updateArray['gender'] = $data['gender'];
            }

            if (isset($data['city_id'])) {
                $updateArray['state_id'] = $data['city_id'];
            }

            $user->update($updateArray);

            if (isset($data['avatar'])) {
                $path = uploadFile("user-$user->id", $data['avatar']);
                $user->update(['avatar' => $path]);
            }


            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::UPDATED), data: UserResource::make($user->load('state'))));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::UPDATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
