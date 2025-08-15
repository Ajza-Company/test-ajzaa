<?php

namespace App\Services\Supplier\Product;

use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use App\Http\Resources\v1\Admin\Variant\A_ShortVariantResource;

class A_DeleteProductService
{
    /**
     * Create a new instance.
     */
    public function __construct()
    {

    }

    /**
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    public function delete($variant): JsonResponse
    {
        \DB::beginTransaction();
        try {

            $variant->delete();

            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::DELETED)));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::DELETE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
