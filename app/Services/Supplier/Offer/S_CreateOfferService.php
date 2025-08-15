<?php

namespace App\Services\Supplier\Offer;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Models\Store;
use App\Models\StoreProductOffer;
use App\Repositories\Supplier\Offer\Create\S_CreateOfferInterface;
use App\Repositories\Supplier\Offer\Insert\S_InsertOfferInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class S_CreateOfferService
{
    /**
     * Create a new instance.
     *
     * @param S_CreateOfferInterface $createOffer
     */
    public function __construct(private S_CreateOfferInterface $createOffer)
    {
    }

    /**
     * Create a new offer.
     *
     * @param array $data
     * @param Store $store
     * @return JsonResponse
     */
    public function create(array $data, Store $store): JsonResponse
    {

        try {
            $offerExist = StoreProductOffer::query()
                ->where('store_product_id', $data['product_id'])
                ->where('expires_at', '>=', Carbon::now())
                ->exists();

            if ($offerExist) {
                return response()->json(errorResponse(trans(ErrorMessageEnum::CREATE)), 400);
            }

            $product = $store->storeProducts()->find($data['product_id']);

            if ($data['type'] == 'percentage' && $data['discount'] > 100) {
                return response()->json(errorResponse(trans(ErrorMessageEnum::CREATE)), 400);
            }

            if ($data['type'] == 'fixed' && $data['discount'] > $product->price) {
                return response()->json(errorResponse(trans(ErrorMessageEnum::CREATE)), 400);
            }

            $this->createOffer->create([
                'store_id' => $store->id,
                'store_product_id' => $data['product_id'],
                'type' => $data['type'],
                'discount' => $data['discount'],
                'expires_at' => $data['expires_at'] ?? null,
                'ajza_offer'=> isset($data['is_ajza_offer']) ? $data['is_ajza_offer'] :0
            ]);

            return response()->json(successResponse(trans(SuccessMessagesEnum::CREATED)));
        } catch (\Exception $ex) {
            return response()->json(errorResponse(trans(ErrorMessageEnum::CREATE), $ex->getMessage()), 500);
        }
    }
}
