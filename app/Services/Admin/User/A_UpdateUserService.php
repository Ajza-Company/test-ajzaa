<?php

namespace App\Services\Admin\User;

use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\v1\User\UserResource;

class A_UpdateUserService
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
            // إلغاء الـ validation على الرقم
            // if (!isValidPhone($data['full_mobile'])) {
            //     return response()->json(errorResponse(message: 'Invalid number detected! Let's try a different one.'),Response::HTTP_BAD_REQUEST);
            // }

            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' =>isset($data['password']) ? Hash::make($data['password']): $user->password,
                'full_mobile' => $data['full_mobile']
            ]);

            if (isset($data['avatar'])) {
                $path = uploadFile("user-$user->id", $data['avatar']);
                $user->update(['avatar' => $path]);
            }


            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::UPDATED), data: UserResource::make($user)));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::UPDATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
