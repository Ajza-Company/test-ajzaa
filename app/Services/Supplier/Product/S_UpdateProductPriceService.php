<?php

namespace App\Services\Supplier\Product;

use App\Models\Product;
use App\Models\StoreProduct;
use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class S_UpdateProductPriceService
{
    /**
     *
     * @param array $data
     * @param $store_id
     * @return JsonResponse
     */
    public function updatePrice(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            $product = StoreProduct::where('id', $data['product_id'])->first();

            $product->update(['price' => $data['price']]);

            DB::commit();

            return response()->json(successResponse(message: trans('general.products_price_updated_successfully')));
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
