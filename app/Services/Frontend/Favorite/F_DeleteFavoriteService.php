<?php

namespace App\Services\Frontend\Favorite;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class F_DeleteFavoriteService
{
    /**
     *
     * @param User $user
     * @param string|null $store_product_id
     * @return JsonResponse
     */
    public function delete(User $user, ?string $store_product_id = null): JsonResponse
    {
        try {
            if ($store_product_id === null) {
                // Remove all favorites for the user
                $user->favorites()->delete();
            } else {
                // Remove specific favorite
                $decoded_store_product_id = decodeString($store_product_id);
                $user->favorites()->where('store_product_id', $decoded_store_product_id)->delete();
            }

            return response()->json(successResponse(message: trans(SuccessMessagesEnum::DELETED)));
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
