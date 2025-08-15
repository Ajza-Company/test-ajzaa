<?php

namespace App\Services\Supplier\Offer;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Models\StoreProductOffer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class S_DeleteOfferService
{
    /**
     *
     * @param StoreProductOffer $offer
     * @return JsonResponse
     */
    public function delete(StoreProductOffer $offer): JsonResponse
    {
        try {
            $offer->update([
                'expires_at' => Carbon::now()
            ]);
            return response()->json(successResponse(trans(SuccessMessagesEnum::DELETED)));
        } catch (\Exception $ex) {
            return response()->json(errorResponse(trans(ErrorMessageEnum::DELETE), $ex->getMessage()));
        }
    }
}
