<?php

namespace App\Services\Frontend\Favorite;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Models\StoreProduct;
use App\Models\User;
use App\Repositories\Frontend\ProductFavorite\Create\F_CreateProductFavoriteInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class F_CreateFavoriteService
{
    /**
     * Create a new instance.
     *
     * @param F_CreateProductFavoriteInterface $createProductFavorite
     */
    public function __construct(private F_CreateProductFavoriteInterface $createProductFavorite)
    {

    }

    /**
     *
     * @param User $user
     * @param array $data
     *
     * @return JsonResponse
     */
    public function create(User $user, array $data): JsonResponse
    {
        try {
            $product_id = $data['product_id'];
            unset($data['product_id']);
            $data['user_id'] = $user->id;
            $data['store_product_id'] = $product_id;
            $this->createProductFavorite->create($data, $data);

            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CREATED)));
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
