<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Enums\ErrorMessageEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\Product\F_ProductResource;
use App\Http\Resources\v1\Frontend\Product\F_ShortProductResource;
use App\Models\Product;
use App\Models\StoreProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class F_ProductController extends Controller
{
    public function __construct()
    {
    }

    /**
     * @param string|null $store_id
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function __invoke(string $store_id = null)
    {
        try {
            $products = StoreProduct::query()
                ->when($store_id, function ($query) use ($store_id) {
                    $query->where('store_id', decodeString($store_id));
                })
                ->where('quantity', '>', 0)
                ->whereHas('product.localized')
                ->with([
                    'product' => function ($query) {
                        $query->whereHas('localized')->with(['localized']);
                    },
                    'favorite' => function ($query) {
                        $query->where('user_id', auth('api')->id());
                    },
                    'store',
                    'offer',
                    'store.company.country.localized'
                ])
                ->filter(\request())
                ->adaptivePaginate();

            return F_ShortProductResource::collection($products);
        } catch (\Exception $ex) {
            \Log::error('Error fetching products for store: ' . $ex->getMessage());
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::FETCH),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param string $product_id
     * @return F_ProductResource
     */
    public function show(string $product_id)
    {
        $decoded_id = decodeString($product_id);

        $product = StoreProduct::query()
            ->whereHas('product.localized')
            ->with([
                'product' => fn($q) => $q->whereHas('localized')->with('localized'),
                'favorite' => fn($q) => $q->where('user_id', auth('api')->id()),
                'offer',
                'store' => [
                    'company' => ['localized'],
                    'area' => [
                        'localized',
                        'state' => ['localized']
                    ]
                ]
            ])
            ->findOrFail($decoded_id);

        return F_ProductResource::make($product);
    }
}
