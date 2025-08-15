<?php

namespace App\Services\Admin\Category;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class A_DeleteCategoryService
{
    /**
     *
     * @return JsonResponse
     */
    public function delete($Category): JsonResponse
    {
        \DB::beginTransaction();
        try {
            $Category->delete();

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
