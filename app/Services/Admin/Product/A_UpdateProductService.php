<?php

namespace App\Services\Admin\Product;

use App\Models\Product;
use Illuminate\Support\Arr;
use App\Models\VariantValue;
use App\Models\ProductLocale;
use Illuminate\Http\Response;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use App\Http\Resources\v1\Admin\Product\A_ShortProductResource;

class A_UpdateProductService
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
    public function update(array $data,$product): JsonResponse
    {
        \DB::beginTransaction();
        try {

            $product->update(Arr::except($data, ['localized','variate']));

            if (isset($data['image'])) {
                if ($product->image) {
                    deleteFile($product->image);
                }

                $path = uploadFile("product-$product->id", $data['image'], 'public');
                $product->update(['image' => $path]);
            }

            if(isset($data['variate'])){
                foreach($data['variate'] as $variant){
                    VariantValue::updateOrCreate([
                            'variant_category_id'=>$variant['variant_category_id'],
                            'product_id'=>$product->id
                        ],
                        [
                            'value'=>$variant['value'],
                        ]);
                }
            }

            foreach($data['localized'] as $local){
                ProductLocale::updateOrCreate(
                    [
                        'locale_id'=>$local['local_id'],
                        'product_id'=>$product->id
                        ],
                    [
                        'description'=>$local['description'],
                        'name'=>$local['name'],
                    ]
                );

            }

            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::UPDATED), data: A_ShortProductResource::make($product)));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::UPDATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
