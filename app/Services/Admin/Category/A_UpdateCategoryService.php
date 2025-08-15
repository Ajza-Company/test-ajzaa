<?php

namespace App\Services\Admin\Category;

use Illuminate\Http\Response;
use App\Models\CategoryLocale;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\v1\Frontend\Category\F_CategoryResource;

class A_UpdateCategoryService
{
    /**
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    public function update(array $data,$Category): JsonResponse
    {
        \DB::beginTransaction();
        try {

            $Category->update([
                'parent_id'=>isset($data['parent_id'])?$data['parent_id']:null,
            ]);

            foreach($data['localized'] as $local){
                CategoryLocale::updateOrCreate(
                    [
                        'locale_id'=>$local['local_id'],
                        'category_id'=>$Category->id
                    ],
                    [
                        'name'=>$local['name'],
                    ]
                );
            }
            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::UPDATED), data: F_CategoryResource::make($Category)));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::UPDATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
