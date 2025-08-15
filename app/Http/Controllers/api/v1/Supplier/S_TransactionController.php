<?php

namespace App\Http\Controllers\api\v1\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Supplier\Transaction\S_ShortTransactionResource;
use App\Repositories\Supplier\Store\Find\S_FindStoreInterface;
use Illuminate\Http\Request;

class S_TransactionController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param S_FindStoreInterface $findStore
     */
    public function __construct(private S_FindStoreInterface $findStore)
    {

    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(string $store_id)
    {
        $store = $this->findStore->find(decodeString($store_id));

        return S_ShortTransactionResource::collection(
            $store->orders->transactions()->where('payment_status', true)->latest()->paginate());
    }
}
