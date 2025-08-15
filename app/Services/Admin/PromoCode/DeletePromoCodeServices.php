<?php

namespace App\Services\Admin\PromoCode;

use App\Models\PromoCode;
use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;

class DeletePromoCodeServices
{
    /**
     * Create a new instance.
     *
     * @param F_CreateUserInterface $createUser
     */
    public function __construct()
    {

    }

    /**
     *
     * @param PromoCode $promoCode
     *
     * @return JsonResponse
     */
    public function delete(PromoCode $promoCode): JsonResponse
    {
        \DB::beginTransaction();
        try {

            $promoCode->delete();

            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::DELETED)));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
