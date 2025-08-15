<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Enums\ErrorMessageEnum;
use App\Enums\OrderDeliveryMethodEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\SuccessMessagesEnum;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Frontend\Order\F_CancelOrderRequest;
use App\Http\Requests\v1\Frontend\Order\F_CreateOrderRequest;
use App\Http\Requests\v1\Frontend\Order\F_GetInvoiceRequest;
use App\Http\Requests\v1\Frontend\Order\F_SuccessPayRequest;
use App\Http\Resources\v1\Frontend\Order\F_OrderResource;
use App\Http\Resources\v1\Frontend\Order\F_ShortOrderResource;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\StoreProduct;
use App\Models\TransactionAttempt;
use App\Repositories\Frontend\Order\Find\F_FindOrderInterface;
use App\Repositories\Frontend\Store\Find\F_FindStoreInterface;
use App\Services\Frontend\Order\F_CancelOrderService;
use App\Services\Frontend\Order\F_CreateOrderService;
use Illuminate\Http\Response;
use Throwable;

class F_OrderController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param F_CreateOrderService $createOrder
     * @param F_FindStoreInterface $findStore
     * @param F_FindOrderInterface $findOrder
     * @param F_CancelOrderService $cancelOrder
     */
    public function __construct(
        private F_CreateOrderService $createOrder,
        private F_FindStoreInterface $findStore,
        private F_FindOrderInterface $findOrder,
        private F_CancelOrderService $cancelOrder)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = auth('api')->user()->orders()->whereHas('store', function ($q) {
            $q->whereRelation('company', 'is_active', true);
        })->with(['orderProducts' => ['storeProduct'], 'store'])->filter(\request())->latest()->adaptivePaginate();
        return F_ShortOrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(F_CreateOrderRequest $request, string $store_id)
    {
        $store = $this->findStore->find(decodeString($store_id));
        return $this->createOrder->create($request->validated(), auth('api')->user(), $store);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getInvoice(F_GetInvoiceRequest $request, string $store_id)
    {
        $data = $request->validated();
        try {
            $invoiceDetails = [];
            $totalAmount = 0;
            $totalDiscount = 0;

            foreach ($data['order_products'] as $product) {
                $storeProduct = StoreProduct::findOrFail($product['product_id']);

                // Fetch the active offer for the product
                $offer = $storeProduct->offer;

                $discount = 0;
                $amount = $storeProduct->price * $product['quantity'];

                if ($offer) {
                    if ($offer->type === 'fixed') {
                        $discount = $offer->discount;
                        $amount -= $discount;
                    } elseif ($offer->type === 'percentage') {
                        $discount = ($storeProduct->price * $offer->discount / 100) * $product['quantity'];
                        $amount -= $discount;
                    }
                }

                $totalAmount += $amount;
                $totalDiscount += $discount;

                $invoiceDetails[] = [
                    'product_id' => (int)$product['product_id'],
                    'price' => $storeProduct->price,
                    'quantity' => $product['quantity'],
                    'discount' => round($discount, 2),
                    'amount' => round($amount, 2),
                ];
            }

            // Apply promo code if provided
            $promoCodeDiscount = 0;
            if (!empty($data['promo_code'])) {
                $promoCode = PromoCode::where('code', $data['promo_code'])
                    ->where('is_active', 1)
                    ->where(function ($query) {
                        $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
                    })
                    ->where(function ($query) {
                        $query->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                    })
                    ->first();

                if (!$promoCode) {
                    return response()->json(
                        errorResponse(message: trans('Invalid or expired promo code')),
                        Response::HTTP_BAD_REQUEST
                    );
                }

                // Validate promo code usage
                if ($promoCode->max_uses !== null && $promoCode->used_count >= $promoCode->max_uses) {
                    return response()->json(
                        errorResponse(message: trans('Promo code usage limit reached')),
                        Response::HTTP_BAD_REQUEST
                    );
                }

                // Validate minimum order value
                if ($promoCode->min_order_value !== null && $totalAmount < $promoCode->min_order_value) {
                    return response()->json(
                        errorResponse(message: trans('Order value does not meet the minimum required for this promo code')),
                        Response::HTTP_BAD_REQUEST
                    );
                }

                // Apply promo code discount
                if ($promoCode->type === 'fixed') {
                    $promoCodeDiscount = $promoCode->value;
                } elseif ($promoCode->type === 'percentage') {
                    $promoCodeDiscount = $totalAmount * ($promoCode->value / 100);
                    if ($promoCode->max_discount !== null) {
                        $promoCodeDiscount = min($promoCodeDiscount, $promoCode->max_discount);
                    }
                }

                $totalAmount -= $promoCodeDiscount;
            }

            return response()->json(successResponse(message: trans(SuccessMessagesEnum::IMPORTED), data: [
                'invoice_details' => $invoiceDetails,
                'delivery_amount' => 0,
                'total_amount' => round($totalAmount, 2),
                'total_discount' => round($totalDiscount + $promoCodeDiscount, 2),
                'promo_code_discount' => round($promoCodeDiscount, 2),
                'total_amount_after_discount' => round($totalAmount, 2),
            ]));
        } catch (\Exception $ex) {
            return response()->json(
                errorResponse(
                    message: trans(ErrorMessageEnum::IMPORT),
                    error: $ex->getMessage()
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $order_id)
    {
        $order = $this->findOrder->find(decodeString($order_id));
        return F_OrderResource::make($order->load('address'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function cancel(F_CancelOrderRequest $request, string $order_id)
    {
        $order = $this->findOrder->find(decodeString($order_id));
        return $this->cancelOrder->cancel($request->validated(), $order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function export()
    {
        $orders = auth('api')->user()->orders()->with(['orderProducts' => ['storeProduct'], 'store'])->filter(\request())->latest()->get();
        return new OrderExport($orders);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function successPay(string $order_id, F_SuccessPayRequest $request)
    {
        $data = $request->validated();
        $order = Order::whereOrderId($order_id)->first();
        if (!$order) {
            return response()->json(
                errorResponse(message: trans('Order not found')),
                Response::HTTP_NOT_FOUND
            );
        }

        TransactionAttempt::create([
            'order_id' => $order->id,
            'amount' => $data['amount'],
            'type' => 'manual',
            'currency_code' => 'SAR',
            'payment_status' => true,
            'status' => 'paid',
            'paymob_transaction_id' => $data['transaction_id']
        ]);

        $order->update([
            'status' => OrderStatusEnum::ACCEPTED
        ]);

        foreach ($order->orderProducts as $orderProduct) {
            $orderProduct->storeProduct->decrement('quantity', $orderProduct->quantity);
        }

        if ($order->delivery_method == OrderDeliveryMethodEnum::DELIVERY) {
            // $shipment = $this->otoGateway->createShipment($transaction->order);
            //\Log::info('shipment: '.json_encode($shipment));
        }
        return response()->json(
            successResponse(message: trans(SuccessMessagesEnum::VERIFIED)),
            Response::HTTP_OK
        );
    }
}
