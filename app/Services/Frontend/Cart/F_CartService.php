<?php

namespace App\Services\Frontend\Cart;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use App\Models\PromoCode;
use App\Models\StoreProduct;
use Illuminate\Http\Response;
use App\Enums\OrderStatusEnum;
use App\Enums\ErrorMessageEnum;
use Illuminate\Http\JsonResponse;
use App\Enums\SuccessMessagesEnum;
use App\Enums\OrderDeliveryMethodEnum;

class F_CartService
{
    /**
     */
    public function __construct() {
    }

    /**
     * Create a new order.
     *
     * @param array $data
     * @param User $user
     * @return JsonResponse
     */
    public function show(array $data): JsonResponse
    {
        \DB::beginTransaction();
        try {

            
            // Prepare and insert order products
            $orderProducts = $this->prepareOrderProductsBulkInsert($data['order_products']);
            // Calculate the total order amount
            $totalAmount = array_sum(array_column($orderProducts, 'amount'));

            if(isset($data['promo_code'])){
                $promoCode =  PromoCode::where('code',$data['promo_code'])->first();
                $discount = $promoCode->calculateDiscount($totalAmount);
                $cart['discount'] = $discount;
                $totalAmount = $totalAmount - $discount;
            }
            
            $cart['orderProducts'] = $orderProducts;
            $cart['totalAmount'] = $totalAmount;

            \DB::commit();
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CREATED),data:$cart));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(
                errorResponse(
                    message: trans(ErrorMessageEnum::CREATE),
                    error: $ex->getMessage()
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Prepare order products for bulk insert.
     *
     * @param array $products
     * @param Order $order
     * @return array
     */
    private function prepareOrderProductsBulkInsert(array $products): array
    {
        $resultArr = [];

        foreach ($products as $product) {
            $storeProduct = StoreProduct::findOrFail($product['product_id']);

            $offer = $storeProduct->offer;

            $discount = 0;
            $amount = $storeProduct->price * $product['quantity'];

            if ($offer) {
                if ($offer->type === 'fixed') {
                    // Fixed discount: subtract the discount directly from the total amount
                    $discount = $offer->discount;
                    $amount -= $discount;
                } elseif ($offer->type === 'percentage') {
                    // Percentage discount: calculate the discount based on the product price
                    $discount = ($storeProduct->price * $offer->discount / 100) * $product['quantity'];
                    $amount -= $discount;
                }
            }

            $resultArr[] = [
                'store_product_id' => encodeString($product['product_id']),
                "price" => $storeProduct->price,
                "quantity" => $product['quantity'],
                'discount' => round($discount, 2),
                'amount' => round($amount, 2),
            ];
        }

        return $resultArr;
    }
}
