<?php

namespace App\Services\Supplier\Product;

use App\Models\Product;
use Illuminate\Support\Arr;
use App\Models\StoreProduct;
use App\Models\VariantValue;
use App\Models\ProductLocale;
use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use App\Http\Resources\v1\Admin\Product\A_ShortProductResource;

class A_CreateProductService
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
    public function create(array $data ,$store): JsonResponse
    {
        \DB::beginTransaction();
        try {

            $product = Product::create(Arr::except($data, ['localized','variate']));

            if (isset($data['image'])) {
                $path = uploadFile("product-$product->id", $data['image']);
                $product->update(['image' => $path]);
            }

            foreach($data['variate'] as $variant){
                VariantValue::create([
                    'variant_category_id'=>$variant['variant_category_id'],
                    'value'=>$variant['value'],
                    'product_id'=>$product->id
                ]);
            }


            foreach($data['localized'] as $local){
                ProductLocale::create([
                    'locale_id'=>$local['local_id'],
                    'description'=>$local['description'],
                    'name'=>$local['name'],
                    'product_id'=>$product->id
                ]);
            }

            StoreProduct::create([
                'price'=>$data['price'],
                'product_id'=>$product->id,
                'store_id'=>$store
            ]);

            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CREATED), data: A_ShortProductResource::make($product->load('variant','variant.variantCategory','variant.variantCategory.localized','localized'))));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
