<?php

namespace App\Services\Frontend\Order;

use App\DTOs\PaymentRequestDTO;
use App\Enums\ErrorMessageEnum;
use App\Enums\OrderDeliveryMethodEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\SuccessMessagesEnum;
use App\Http\Resources\v1\Frontend\Order\F_ShortOrderResource;
use App\Models\Order;
use App\Models\Store;
use App\Models\StoreProduct;
use App\Models\TransactionAttempt;
use App\Models\User;
use App\Notifications\OrderNotification;
use App\Repositories\Frontend\Order\Create\F_CreateOrderInterface;
use App\Repositories\Frontend\OrderProduct\Insert\F_InsertOrderProductInterface;
use App\Services\Delivery\OtoGateway;
use App\Services\Payment\ClickPayGateway;
use App\Services\Payment\PaymentService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Throwable;

class F_CreateOrderService
{
    /**
     * @param F_CreateOrderInterface $createOrder
     * @param F_InsertOrderProductInterface $insertOrderProduct
     * @param OtoGateway $otoGateway
     */
    public function __construct(
        private F_CreateOrderInterface $createOrder,
        private F_InsertOrderProductInterface $insertOrderProduct,
        private OtoGateway $otoGateway
    ) {
    }

    /**
     * Create a new order.
     *
     * @param array $data
     * @param User $user
     * @param Store $store
     * @return JsonResponse
     * @throws Throwable
     */
    public function create(array $data, User $user, Store $store): JsonResponse
    {
        \DB::beginTransaction();
        try {
            // Create the order with an initial amount of 0
            $order = $this->createOrder->create([
                'order_id' => generateOrderId($store->category->category->order_prefix),
                'user_id' => $user->id,
                'store_id' => $store->id,
                'status' => OrderStatusEnum::PENDING,
                'delivery_method' => $data['delivery_method'],
                'payment_method' => $data['payment_method'],
                'amount' => 0, // Initial amount set to 0
                'address_id' => $data['delivery_method'] == OrderDeliveryMethodEnum::DELIVERY ? $data['address_id'] : null,
                'ajza_percentage' => ajzaSetting()->order_percentage
            ]);

            // Prepare and insert order products
            $orderProducts = $this->prepareOrderProductsBulkInsert($data['order_products'], $order);
            $this->insertOrderProduct->insert($orderProducts);

            // Calculate the total order amount
            $totalAmount = array_sum(array_column($orderProducts, 'amount'));

            // Update the order with the total amount
            $order = tap($order)->update(['amount' => $totalAmount]);

            /*$transaction = TransactionAttempt::create([
                'order_id' => $order->id,
                'amount' => $totalAmount,
                'type' => 'manual',
                'currency_code' => 'SAR'
            ]);

            $gateway = match(config('services.payment.default')) {
                'clickpay' => new ClickPayGateway(),
                default => throw new Exception('Invalid gateway'),
            };

            $paymentService = new PaymentService($gateway);
            $result = $paymentService->createPayment(
                new PaymentRequestDTO(amount: $totalAmount, description: 'Order Payment', cartId: encodeString($transaction->id)),
                $order
            );

            $transaction->update([
                'paymob_iframe_token' => $result->redirectUrl
            ]);*/

            \DB::commit();
            return response()->json(
                successResponse(
                    message: trans(SuccessMessagesEnum::CREATED),
                    data: F_ShortOrderResource::make($order)));
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
    private function prepareOrderProductsBulkInsert(array $products, Order $order): array
    {
        $resultArr = [];

        foreach ($products as $product) {
            $storeProduct = StoreProduct::findOrFail($product['product_id']);

            // Fetch the active offer for the product
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
                "order_id" => $order->id,
                'store_product_id' => $product['product_id'],
                "price" => $storeProduct->price,
                "quantity" => $product['quantity'],
                'discount' => round($discount, 2),
                'amount' => round($amount, 2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        return $resultArr;
    }
}
