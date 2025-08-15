<?php

namespace App\Services\Admin\Slider;

use App\Models\SliderImage;
use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use Throwable;

class F_CreateSliderService
{
    /**
     * Create a new instance.
     *
     */
    public function __construct()
    {

    }

    /**
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function create(): JsonResponse
    {
        \DB::beginTransaction();
        try {

            $slider = SliderImage::create([]);

            $slider->addMediaFromRequest('image')->toMediaCollection('sliders');

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
}
