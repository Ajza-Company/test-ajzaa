<?php

namespace App\Services\Frontend\Auth;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Http\Resources\v1\User\UserResource;
use App\Models\OtpCode;
use App\Models\User;
use App\Repositories\General\FcmToken\Create\G_CreateFcmTokenInterface;
use App\Services\Frontend\SmsService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class F_VerifyOtpCodeService
{
    /**
     * Create a new instance.
     *
     * @param SmsService $smsService
     */
    public function __construct(private SmsService $smsService, private G_CreateFcmTokenInterface $createFcmToken)
    {

    }
    /**
     *
     * @param array $data
     * @return JsonResponse
     */
    public function verify(array $data): JsonResponse
    {
        \DB::beginTransaction();
        try {
            if (!isValidPhone($data['full_mobile'])) {
                return response()->json(errorResponse(message: 'Invalid number detected! Let’s try a different one.'),Response::HTTP_BAD_REQUEST);
            }

            $isValid = $data['code'] == '1111';
            // $isValid = $this->smsService->verifyOTP($data['full_mobile'], $data['code']);

            if ($isValid) {
                $user = User::where([
                    'full_mobile' => $data['full_mobile'],
                    'is_registered' => true,
                    'is_active' => true
                ])->first();

                $returnArr = [];
                $token = null;

                if ($user) {
                    if (isset($data['fcm_token'])) {
                        $this->createFcmToken->create([
                            'user_id' => $user->id,
                            'token' => $data['fcm_token']
                        ]);
                    }
                    $returnArr = UserResource::make($user);
                    $token = $user->createToken('auth_token')->plainTextToken;
                }

                \DB::commit();
                return response()->json(successResponse(message: trans(SuccessMessagesEnum::VERIFIED), data: $returnArr, token: $token));
            }

            return response()->json(errorResponse(message: trans(ErrorMessageEnum::VERIFY)), Response::HTTP_BAD_REQUEST);

        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::VERIFY),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
