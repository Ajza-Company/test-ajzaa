<?php

namespace App\Services\Admin\Store;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Http\Resources\v1\Admin\Store\A_StoreResource;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Repositories\Supplier\StoreHour\Insert\S_InsertStoreHourInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class A_UpdateStoreService
{
    /**
     * Create a new instance.
     *
     * @param S_InsertStoreHourInterface $insertStoreHour
     */
    public function __construct(private S_InsertStoreHourInterface $insertStoreHour)
    {
    }

    /**
     * Update store with all details
     *
     * @param array $data
     * @param Store $store
     * @return JsonResponse
     */
    public function update(array $data, Store $store): JsonResponse
    {
        try {
            // تحديث البيانات الأساسية
            $dataToUpdate = Arr::except($data, ['image', 'hours', 'category_id']);
            
            if (!empty($dataToUpdate)) {
                $store->update($dataToUpdate);
            }

            // تحديث الصورة إذا وجدت
            if (isset($data['image'])) {
                if ($store->image) {
                    deleteFile($store->image);
                }
                $path = uploadFile("store-$store->id", $data['image']);
                $store->update(['image' => $path]);
            }

            // تحديث الفئة إذا وجدت
            if (isset($data['category_id'])) {
                StoreCategory::updateOrCreate(
                    ['store_id' => $store->id],
                    ['category_id' => $data['category_id']]
                );
            }

            // تحديث ساعات العمل إذا وجدت
            if (isset($data['hours'])) {
                $store->hours()->delete();
                $this->insertStoreHour->insert($this->prepareBulkInsert($data['hours'], $store));
            }

            return response()->json(
                successResponse(
                    message: trans(SuccessMessagesEnum::UPDATED),
                    data: A_StoreResource::make($store->fresh()->load([
                        'company.localized', 
                        'area.localized', 
                        'category.category.localized',
                        'hours'
                    ]))
                )
            );
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::UPDATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Prepare bulk insert data for store hours
     *
     * @param array $hours
     * @param Store $store
     * @return array
     */
    private function prepareBulkInsert(array $hours, Store $store): array
    {
        $resultArr = [];

        foreach ($hours as $hour) {
            $resultArr[] = [
                "store_id" => $store->id,
                'day' => $hour['day'],
                "open_time" => $hour['open_time'],
                "close_time" => $hour['close_time'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return $resultArr;
    }
}
