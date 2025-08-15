<?php

namespace App\Services\Admin\State;

use App\Models\State;
use App\Enums\RoleEnum;
use App\Models\StateLocale;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use App\Http\Resources\v1\Admin\State\A_ShortStateResource;

class F_CreateStateService
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

            $state = State::create(Arr::except($data, ['localized']));

            foreach($data['localized'] as $local){
                StateLocale::create([
                    'locale_id'=>$local['local_id'],
                    'name'=>$local['name'],
                    'state_id'=>$state->id
                ]);
            }
    
            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CREATED), data: A_ShortStateResource::make($state)));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
