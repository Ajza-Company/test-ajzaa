<?php

namespace App\Services\Admin\Setting;

use App\Models\Setting;
use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use App\Http\Resources\v1\Admin\Setting\A_ShortSettingResource;

class F_CreateSettingService
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
     * @param array $data
     *
     * @return JsonResponse
     */
    public function create(array $data): JsonResponse
    {
        \DB::beginTransaction();
        try {

            $setting = Setting::latest()->first();

            if($setting){
                $setting->update([
                    'setting'=>json_encode($data)
                ]);
            }else{
                $setting = Setting::create([
                    'setting'=>json_encode($data)
                ]);
            }


    
            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CREATED), data: A_ShortSettingResource::make($setting)));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
