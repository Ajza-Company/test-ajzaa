<?php

namespace App\Services\Admin\Slider;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class F_DeleteSliderService
{
    /**
     *
     * @param $slider
     * @return JsonResponse
     */
    public function delete($slider): JsonResponse
    {
        try {
            $slider->delete();

            return response()->json(successResponse(message: trans(SuccessMessagesEnum::DELETED)));
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::DELETE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
