<?php

namespace App\Services\Supplier\Store;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Http\Resources\v1\Supplier\Store\S_StoreResource;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Repositories\Supplier\StoreHour\Insert\S_InsertStoreHourInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class S_UpdateStoreService
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
     *
     * @param array $data
     * @param Store $store
     * @return JsonResponse
     */
    public function update(array $data, Store $store): JsonResponse
    {
        try {

            $dataToUpdate = Arr::except($data['data'], ['image']);
            $dataToUpdate['can_add_products'] = $data['data']['can_add_products'] ?? $store->can_add_products;

            if (!empty($dataToUpdate)) {
                $store->update($dataToUpdate);
            }

            StoreCategory::updateOrCreate([
                'store_id' => $store->id],[
                'category_id' => userCompany()->category_id
            ]);


            if (isset($data['image'])) {
                if ($store->image) {
                    deleteFile($store->image);
                }
                $path = uploadFile("store-$store->id", $data['image']);
                $store->update(['image' => $path]);
            }

            if (isset($data['hours'])) {
                $store->hours()->delete();
                $this->insertStoreHour->insert($this->prepareBulkInsert($data['hours'], $store));
            }

            return response()->json(
                successResponse(
                    message: trans(SuccessMessagesEnum::UPDATED),
                    data: S_StoreResource::make($store->load('company', 'company.localized', 'hours')))
            );
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::UPDATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
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
                'updated_at' => Carbon::now()
            ];
        }

        return $resultArr;
    }
}
