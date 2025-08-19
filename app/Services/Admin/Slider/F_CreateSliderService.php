<?php

namespace App\Services\Admin\Slider;

use App\Models\SliderImage;
use App\Models\Locale;
use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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
        DB::beginTransaction();
        try {
            $imagePath = null;
            
            if (request()->hasFile('image')) {
                $imagePath = request()->file('image')->store('slider', 'public');
            }

            // Get default locale if not provided
            $localeId = request('locale_id', SliderImage::getDefaultLocaleId());
            
            // Verify locale exists, if not use default locale
            if (!Locale::find($localeId)) {
                $localeId = SliderImage::getDefaultLocaleId();
            }

            $slider = SliderImage::create([
                'image' => $imagePath,
                'order' => request('order', 1),
                'is_active' => request('is_active', true),
                'locale_id' => $localeId
            ]);

            DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CREATED)));
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
