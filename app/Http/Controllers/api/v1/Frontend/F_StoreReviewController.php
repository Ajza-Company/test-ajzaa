<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Frontend\StoreReview\F_SubmitReviewRequest;
use App\Repositories\Frontend\Order\Find\F_FindOrderInterface;
use App\Services\Frontend\StoreReview\F_SubmitStoreReviewService;
use Illuminate\Http\Request;

class F_StoreReviewController extends Controller
{
    /**
     *
     * @param F_SubmitStoreReviewService $submitStoreReviewService
     * @param F_FindOrderInterface $findOrder
     */
    public function __construct(
        private F_SubmitStoreReviewService $submitStoreReviewService,
        private F_FindOrderInterface $findOrder)
    {

    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(string $order_id, F_SubmitReviewRequest $request)
    {
        $order = $this->findOrder->find(decodeString($order_id));
        return $this->submitStoreReviewService->create($order, auth('api')->id(), $request->validated());
    }
}
