<?php

namespace App\Services\Admin\State;

use App\Models\StateLocale;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use App\Http\Resources\v1\Admin\State\A_ShortStateResource;

class F_UpdateStateService
{
    /**
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    public function update(array $data,$state): JsonResponse
    {
        \DB::beginTransaction();
        try {
            $state->update(Arr::except($data, ['localized']));


            foreach($data['localized'] as $local){
                StateLocale::updateOrCreate(
                    [
                        'locale_id'=>$local['local_id'],
                        'state_id'=>$state->id
                        ],
                    [
                        'name'=>$local['name'],    
                    ]
                );

            }

            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::UPDATED), data: A_ShortStateResource::make($state)));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::UPDATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
