<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Frontend\RepReview\F_SubmitRepReviewRequest;
use App\Models\RepOrder;
use App\Models\RepReview;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class F_RepReviewController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $rep_order_id, F_SubmitRepReviewRequest $request)
    {
        $data = $request->validated();
        $order = RepOrder::findOrFail(decodeString($rep_order_id));

        try {
            $data = [
                'user_id' => $order->user_id,
                'rep_order_id' => $order->id,
                'rep_id' => $order->repChat?->user1_id,
                ...$data
            ];
            RepReview::create($data);

            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CREATED)));
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
