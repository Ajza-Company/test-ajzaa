<?php

namespace App\Services\Supplier\Product;

use App\Models\Product;
use App\Models\StoreProduct;
use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class S_CreateSelectProductService
{
    /**
     *
     * @param array $data
     * @param $store_id
     * @return JsonResponse
     */
    public function create(array $data , $store): JsonResponse
    {
        try {
            DB::beginTransaction();

            $store_id = $store->id;

            if ($data['is_select_all'] == true) {
                $data['product_ids'] = Product::where('category_id', $store->company->category_id)
                    ->when(request()->has('car_brand'), function ($q) use ($data) {
                        return $q->whereHas('carAttributes', function ($query) use ($data) {
                            $query->where('car_brand_id', request()->car_brand);
                        });
                    })
                    ->when(request()->has('car_model'), function ($q) use ($data) {
                        return $q->whereHas('carAttributes', function ($query) use ($data) {
                            $query->where('car_model_id', request()->car_model);
                        });
                    })
                    ->when(request()->has('year'), function ($q) use ($data) {
                        return $q->whereHas('carAttributes', function ($query) use ($data) {
                            $query->where('year', request()->year);
                        });
                    })
                    ->pluck('id')
                    ->toArray();
            }

            Product::whereIn('id', $data['product_ids'])->chunk(100, function ($products) use ($store_id) {
                foreach ($products as $product) {
                    StoreProduct::updateOrCreate(
                        [
                            'store_id' => $store_id,
                            'product_id' => $product->id
                        ], // Search for an existing record
                        [
                            'price' => $product->price,
                            'updated_at' => now()
                        ] // Update these values if found, otherwise insert
                    );
                }
            });

            DB::commit();

            return response()->json(successResponse(message: trans('general.products_created_successfully')));
        } catch (\Exception $ex) {
            DB::rollBack();

            return response()->json(errorResponse(
                message: ErrorMessageEnum::CREATE,
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

}
