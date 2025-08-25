<?php

namespace App\Services\Supplier\Store;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Http\Resources\v1\Supplier\Store\S_StoreResource;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Models\StoreUser;
use App\Repositories\Supplier\Store\Create\S_CreateStoreInterface;
use App\Repositories\Supplier\StoreHour\Insert\S_InsertStoreHourInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class S_CreateStoreService
{
    /**
     * Create a new instance.
     *
     * @param S_CreateStoreInterface $createStore
     * @param S_InsertStoreHourInterface $insertStoreHour
     */
    public function __construct(private S_CreateStoreInterface $createStore,
                                private S_InsertStoreHourInterface $insertStoreHour)
    {
    }

    /**
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    public function create(array $data, int $user_id = null): JsonResponse
    {
        try {
            $data['data']['is_active'] = false;
            $data['data']['can_add_products'] = $data['data']['can_add_products'] ?? true;

            // تحديد الترتيب التلقائي
            $maxOrder = Store::max('sort_order') ?? 0;
            $data['data']['sort_order'] = $maxOrder + 1;

            $store = $this->createStore->create([
                'company_id' => userCompany()->id,
                ...Arr::except($data['data'], ['image'])
            ]);

            StoreCategory::create([
                'store_id' => $store->id,
                'category_id' => userCompany()->category_id
            ]);

            // Add car brands if provided
            if (isset($data['data']['car_brand_id']) && is_array($data['data']['car_brand_id'])) {
                foreach ($data['data']['car_brand_id'] as $carBrandId) {
                    DB::table('store_car_brands')->insert([
                        'store_id' => $store->id,
                        'car_brand_id' => $carBrandId,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }

            if (isset($data['image'])) {
                $path = uploadFile("store-$store->id", $data['image']);
                $store->update(['image' => $path]);
            }

            $this->insertStoreHour->insert($this->prepareBulkInsert($data['hours'], $store));

            DB::table('store_users')->insert([
                'store_id' => $store->id,
                'user_id' => $user_id ?? auth('api')->id(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            return response()->json(
                successResponse(message: trans(SuccessMessagesEnum::CREATED),
                    data: S_StoreResource::make($store->load('company', 'company.localized', 'hours'))));
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
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
                'updated_at' => Carbon::now(),
            ];
        }

        return $resultArr;
    }
}
