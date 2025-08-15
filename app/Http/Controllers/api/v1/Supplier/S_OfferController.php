<?php

namespace App\Http\Controllers\api\v1\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Supplier\Offer\S_CreateOfferRequest;
use App\Http\Resources\v1\Supplier\Offer\S_OfferResource;
use App\Repositories\Supplier\Offer\Find\S_FindOfferInterface;
use App\Repositories\Supplier\Store\Find\S_FindStoreInterface;
use App\Services\Supplier\Offer\S_CreateOfferService;
use App\Services\Supplier\Offer\S_DeleteOfferService;
use Illuminate\Http\Request;

class S_OfferController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param S_CreateOfferService $createOffer
     * @param S_FindStoreInterface $findStore
     * @param S_DeleteOfferService $deleteOffer
     * @param S_FindOfferInterface $findOffer
     */
    public function __construct(
        private S_CreateOfferService $createOffer,
        private S_FindStoreInterface $findStore,
        private S_DeleteOfferService $deleteOffer,
        private S_FindOfferInterface $findOffer)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $store_id)
    {
        $store = $this->findStore->find(decodeString($store_id));
        return S_OfferResource::collection(
            $store->offers()->with(['storeProduct' => ['product.localized']])->latest()->adaptivePaginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(S_CreateOfferRequest $request, string $store_id)
    {
        $store = $this->findStore->find(decodeString($store_id));
        return $this->createOffer->create($request->validated(), $store);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $offer_id)
    {
        $offer = $this->findOffer->find(decodeString($offer_id));
        return $this->deleteOffer->delete($offer);
    }
}
