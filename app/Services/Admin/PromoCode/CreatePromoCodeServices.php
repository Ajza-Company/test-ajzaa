<?php

namespace App\Services\Admin\PromoCode;

use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use App\Repositories\Admin\PromoCode\Create\A_CreatePromoCodeInterface;

class CreatePromoCodeServices
{
    /**
     * Create a new instance.
     *
     * @param F_CreateUserInterface $createUser
     */
    public function __construct(private A_CreatePromoCodeInterface $createPromoCode,)
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

            $PromoCode = $this->createPromoCode($data);

            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CREATED)));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function createPromoCode(array $data)
    {
        return $this->createPromoCode->create([
            'code' => $data['code'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
            'value' => $data['value'],
            'max_uses' => $data['max_uses'] ?? null,
            'used_count' => 0,
            'min_order_value' => $data['min_order_value'] ?? null,
            'max_discount' => $data['max_discount'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'starts_at' => $data['starts_at'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
        ]);
    }

}
