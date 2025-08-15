<?php

namespace App\Services\Admin\Category;

use App\Enums\RoleEnum;
use Illuminate\Http\Response;
use App\Models\Category;
use App\Models\CategoryLocale;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\v1\Frontend\Category\F_CategoryResource;

class A_CreateCategoryService
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
    public function create(array $data): JsonResponse
    {
        \DB::beginTransaction();
        try {

            /*$existingCategory = CategoryLocale::where(function($query) use ($data) {
                $query->where(['name' => $data['localized'][0]['name'], 'locale_id' => $data['localized'][0]['local_id']])
                    ->orWhere(['name' => $data['localized'][1]['name'], 'locale_id' => $data['localized'][1]['local_id']]);
            })->first();

            if ($existingCategory) {
                return response()->json(errorResponse(
                    message: 'Category already exists',
                    error: 'category already exists'),
                    Response::HTTP_BAD_REQUEST);
            }*/

            $category = Category::create([
                'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : null,
            ]);

            foreach ($data['localized'] as $local) {
                CategoryLocale::create([
                    'category_id' => $category->id,
                    'name' => $local['name'],
                    'locale_id' => $local['local_id']
                ]);
            }

            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CREATED), data: F_CategoryResource::make($category)));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
